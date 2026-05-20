"""
Flask ML API - Pengelompokan Mahasiswa Felder-Silverman
Pipeline IDENTIK dengan ML asli:
  - Feature Engineering (interaction + dominansi)
  - Cube Root Transform
  - StandardScaler
  - PCA 3 Komponen
  - KMeans K=4
  - Kelompok Belajar HOMOGEN (per cluster)
  - Kelompok Tugas HETEROGEN (round-robin antar cluster)

Jalankan:
    pip install -r requirements.txt
    python app.py
"""

from flask import Flask, request, jsonify
import numpy as np
from sklearn.preprocessing import StandardScaler
from sklearn.cluster import KMeans
from sklearn.decomposition import PCA
import joblib
import os

app = Flask(__name__)

GROUP_SIZE   = 5   # default ukuran kelompok, bisa di-override via request
RANDOM_STATE = 42  # KMeans tetap stabil, shuffle kelompok bisa None


# ─────────────────────────────────────────────────────────────────────────────
# PIPELINE ML — identik dengan script Python asli
# ─────────────────────────────────────────────────────────────────────────────

def build_features(skor_list):
    """
    Input: list of dict dengan key:
        ar, si, vv, sg  (skor integer, bisa negatif)
    Output: numpy array shape (n, 11) — fitur lengkap sebelum scaler
    """
    ar = np.array([m['ar'] for m in skor_list], dtype=float)
    si = np.array([m['si'] for m in skor_list], dtype=float)
    vv = np.array([m['vv'] for m in skor_list], dtype=float)
    sg = np.array([m['sg'] for m in skor_list], dtype=float)

    # Feature engineering — sama persis dengan ML asli
    fitur = np.column_stack([
        ar, si, vv, sg,
        ar * si,                          # ARxSI
        ar * vv,                          # ARxVV
        ar * sg,                          # ARxSG
        si * vv,                          # SIxVV
        si * sg,                          # SIxSG
        vv * sg,                          # VVxSG
        np.abs(ar) + np.abs(si) + np.abs(vv) + np.abs(sg),  # Dominansi
    ])

    return fitur


def run_pipeline(mahasiswa_list, group_size=GROUP_SIZE):
    """
    Jalankan full ML pipeline pada list mahasiswa.

    Parameter mahasiswa_list: list of dict, tiap dict punya:
        nrp, nama,
        skor_active_reflective (= ar),
        skor_sensing_intuitive (= si),
        skor_visual_verbal     (= vv),
        skor_sequential_global (= sg)

    Return:
        homogen_groups  : dict {kelompok_N: [mahasiswa, ...]}
        heterogen_groups: dict {kelompok_N: [mahasiswa, ...]}
        cluster_info    : dict metadata cluster per mahasiswa
    """
    n = len(mahasiswa_list)
    if n == 0:
        return {}, {}, []

    # Mapping skor ke format internal
    skor_list = []
    for m in mahasiswa_list:
        skor_list.append({
            'ar': float(m.get('skor_active_reflective', 0)),
            'si': float(m.get('skor_sensing_intuitive', 0)),
            'vv': float(m.get('skor_visual_verbal', 0)),
            'sg': float(m.get('skor_sequential_global', 0)),
        })

    # 1. Feature engineering
    X = build_features(skor_list)

    # 2. Cube root transform
    X_cbrt = np.cbrt(X)

    # 3. Standard scaler
    scaler = StandardScaler()
    X_scaled = scaler.fit_transform(X_cbrt)

    # 4. PCA 3 komponen
    n_components = min(3, n, X_scaled.shape[1])
    pca = PCA(n_components=n_components, random_state=42)
    X_pca = pca.fit_transform(X_scaled)

    # 5. KMeans K=4 (atau kurang kalau data sedikit)
    k = min(4, n)
    kmeans = KMeans(n_clusters=k, random_state=42, n_init=100, max_iter=1000)
    labels = kmeans.fit_predict(X_pca)

    # Tambahkan cluster ke tiap mahasiswa
    for i, m in enumerate(mahasiswa_list):
        m['_cluster'] = int(labels[i])

    # Profil cluster (rata-rata skor per cluster)
    cluster_profile = {}
    for c in range(k):
        members = [mahasiswa_list[i] for i in range(n) if labels[i] == c]
        if members:
            cluster_profile[c] = {
                'ar': np.mean([float(x.get('skor_active_reflective', 0)) for x in members]),
                'si': np.mean([float(x.get('skor_sensing_intuitive', 0)) for x in members]),
                'vv': np.mean([float(x.get('skor_visual_verbal', 0)) for x in members]),
                'sg': np.mean([float(x.get('skor_sequential_global', 0)) for x in members]),
            }

    # ── KELOMPOK BELAJAR HOMOGEN ──────────────────────────────────────────────
    # Mahasiswa dalam cluster yang sama dikelompokkan bersama
    homogen_groups = {}
    group_num = 1

    for cluster_id in sorted(set(labels)):
        cluster_members = [m for m in mahasiswa_list if m['_cluster'] == cluster_id]
        # Shuffle untuk variasi (random_state=None agar berubah tiap run)
        np.random.shuffle(cluster_members)

        for i in range(0, len(cluster_members), group_size):
            batch = cluster_members[i:i + group_size]
            homogen_groups[f'kelompok_{group_num}'] = _strip_internal(batch)
            group_num += 1

    # ── KELOMPOK TUGAS HETEROGEN ──────────────────────────────────────────────
    # Round-robin antar cluster supaya tiap kelompok beragam
    cluster_pools = {}
    for cluster_id in sorted(set(labels)):
        pool = [m.copy() for m in mahasiswa_list if m['_cluster'] == cluster_id]
        np.random.shuffle(pool)
        cluster_pools[cluster_id] = pool

    heterogen_groups = {}
    group_num = 1

    while any(len(p) > 0 for p in cluster_pools.values()):
        current_group = []
        used_clusters = set()

        while len(current_group) < group_size:
            available = [c for c, p in cluster_pools.items() if len(p) > 0]
            if not available:
                break

            unused = [c for c in available if c not in used_clusters]
            candidates = unused if unused else available

            # Pilih cluster dengan anggota terbanyak (strategi ML asli)
            selected = max(candidates, key=lambda c: len(cluster_pools[c]))
            member = cluster_pools[selected].pop(0)
            current_group.append(member)
            used_clusters.add(selected)

        if current_group:
            heterogen_groups[f'kelompok_{group_num}'] = _strip_internal(current_group)
            group_num += 1

    # Cluster info per mahasiswa untuk metadata
    cluster_info = []
    for m in mahasiswa_list:
        c = m.get('_cluster', 0)
        profile = cluster_profile.get(c, {})
        cluster_info.append({
            'nrp':              m.get('nrp'),
            'cluster':          c,
            'cluster_profile':  _interpret_cluster(profile),
        })

    return homogen_groups, heterogen_groups, cluster_info


def _strip_internal(members):
    """Hapus key internal (_cluster) sebelum dikirim ke response."""
    clean = []
    for m in members:
        d = {k: v for k, v in m.items() if not k.startswith('_')}
        clean.append(d)
    return clean


def _interpret_cluster(profile):
    """Buat label interpretasi seperti di ML asli."""
    def label(val, pos, neg):
        if val >= 2:   return pos
        if val <= -2:  return neg
        return 'Balanced'

    ar = label(profile.get('ar', 0), 'Active',     'Reflective')
    si = label(profile.get('si', 0), 'Sensing',    'Intuitive')
    vv = label(profile.get('vv', 0), 'Visual',     'Verbal')
    sg = label(profile.get('sg', 0), 'Sequential', 'Global')
    return f'{ar} - {si} - {vv} - {sg}'


# ─────────────────────────────────────────────────────────────────────────────
# ENDPOINTS
# ─────────────────────────────────────────────────────────────────────────────

@app.route('/cluster', methods=['POST'])
def cluster():
    """
    POST /cluster

    Request JSON:
    {
        "kelas_id":   1,
        "group_size": 5,          // opsional, default 5
        "mahasiswa": [
            {
                "nrp":                    "12345",
                "nama":                   "Budi Santoso",
                "skor_active_reflective": 3,
                "skor_sensing_intuitive": -1,
                "skor_visual_verbal":     5,
                "skor_sequential_global": -3
            },
            ...
        ]
    }

    Response JSON:
    {
        "success": true,
        "kelas_id": 1,
        "total_mahasiswa": 30,
        "cluster_info": [...],
        "homogen": {
            "kelompok_1": [...],
            "kelompok_2": [...]
        },
        "heterogen": {
            "kelompok_1": [...],
            "kelompok_2": [...]
        }
    }
    """
    try:
        data = request.get_json(force=True)

        if not data or 'mahasiswa' not in data:
            return jsonify({'success': False, 'message': 'Field "mahasiswa" wajib diisi'}), 400

        mahasiswa_list = data['mahasiswa']
        kelas_id       = data.get('kelas_id')
        group_size     = int(data.get('group_size', GROUP_SIZE))

        if len(mahasiswa_list) == 0:
            return jsonify({'success': False, 'message': 'List mahasiswa kosong'}), 400

        if len(mahasiswa_list) < 2:
            return jsonify({'success': False, 'message': 'Minimal 2 mahasiswa untuk clustering'}), 400

        homogen, heterogen, cluster_info = run_pipeline(mahasiswa_list, group_size)

        return jsonify({
            'success':          True,
            'kelas_id':         kelas_id,
            'total_mahasiswa':  len(mahasiswa_list),
            'group_size':       group_size,
            'cluster_info':     cluster_info,
            'homogen':          homogen,
            'heterogen':        heterogen,
        })

    except Exception as e:
        return jsonify({'success': False, 'message': str(e)}), 500


@app.route('/health', methods=['GET'])
def health():
    return jsonify({
        'status':  'ok',
        'service': 'ML Clustering Felder-Silverman',
        'pipeline': 'FeatureEng → CubeRoot → StandardScaler → PCA(3) → KMeans(K=4)',
    })


if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000, debug=True)
