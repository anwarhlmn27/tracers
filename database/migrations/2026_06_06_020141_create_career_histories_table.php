<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
    Schema::create('career_histories', function (Blueprint $table) {
        $table->uuid('id')->primary();
        $table->foreignUuid('student_id')->constrained('students')->onDelete('cascade');
        $table->string('nama_instansi');
        $table->string('posisi');
        $table->date('tanggal_mulai');
        $table->date('tanggal_selesai')->nullable();
        $table->enum('jenis', ['bekerja', 'wiraswasta', 'studi_lanjut']);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('career_histories');
    }
};
