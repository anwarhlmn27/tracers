<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->string('status_alumni')->nullable()->comment('Bekerja (full time / part time), Belum memungkinkan bekerja, Wiraswasta, Melanjutkan Pendidikan, Tidak kerja tetapi sedang mencari kerja');
            $table->string('nama_perusahaan')->nullable();
            $table->string('jabatan')->nullable();
            $table->string('tempat_kerja')->nullable()->comment('Lokal, Nasional, Multinasional');
            $table->integer('response_rate')->nullable();
            $table->string('waktu_tunggu_kerja')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn([
                'status_alumni',
                'nama_perusahaan',
                'jabatan',
                'tempat_kerja',
                'response_rate',
                'waktu_tunggu_kerja'
            ]);
        });
    }
};
