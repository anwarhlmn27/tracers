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
    Schema::create('students', function (Blueprint $table) {
        $table->uuid('id')->primary();
        $table->foreignUuid('user_id')->constrained('users')->onDelete('cascade');
        $table->foreignUuid('prodi_id')->constrained('prodis')->onDelete('cascade');
        $table->string('nim')->unique();
        $table->string('nama_student');
        $table->integer('angkatan');
        $table->enum('status', ['aktif', 'lulus', 'cuti', 'drop_out'])->default('aktif');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
