<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::dropIfExists('kelompok_mahasiswa');
    Schema::dropIfExists('kelompok');

    Schema::create('kelompok', function (Blueprint $table) {
        $table->id('id_kelompok');
        $table->integer('kelas_id');
        $table->string('nama_kelompok', 100);
        $table->enum('tipe', ['homogen', 'heterogen']);
        $table->string('cluster_profile', 255)->nullable();
        $table->timestamps();

        $table->foreign('kelas_id')
              ->references('id_kelas')
              ->on('kelas')
              ->onDelete('cascade')
              ->onUpdate('cascade');
    });

    Schema::create('kelompok_mahasiswa', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('kelompok_id');
        $table->string('mahasiswa_nrp', 10)->collation('utf8mb4_general_ci');
        $table->integer('cluster_id')->nullable();
        $table->timestamps();

        $table->unique(['kelompok_id', 'mahasiswa_nrp']);

        $table->foreign('kelompok_id')
              ->references('id_kelompok')
              ->on('kelompok')
              ->onDelete('cascade')
              ->onUpdate('cascade');

        $table->foreign('mahasiswa_nrp')
              ->references('nrp')
              ->on('mahasiswa')
              ->onDelete('cascade')
              ->onUpdate('cascade');
    });
}

    public function down(): void
    {
        Schema::dropIfExists('kelompok_mahasiswa');
        Schema::dropIfExists('kelompok');
    }
};