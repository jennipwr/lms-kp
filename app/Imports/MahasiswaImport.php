<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;

class MahasiswaImport implements ToCollection
{
    public $accounts = [];

    public function collection(Collection $rows)
    {
        $rows->shift();

        DB::beginTransaction();

        try {
            foreach ($rows as $row) {
                $nrp = $row[0];
                $nama = $row[1];
                $email = $row[2];

                $password = 'itmaranatha12345*';

                $user = User::create([
                    'nama' => $nama,
                    'email' => $email,
                    'password' => Hash::make($password),
                    'role_id' => 3,
                ]);

                Mahasiswa::create([
                    'nrp' => $nrp,
                    'users_id' => $user->id,
                ]);

                $this->accounts[] = [
                    'nrp' => $nrp,
                    'nama' => $nama,
                    'email' => $email,
                    'password' => $password,
                ];
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}