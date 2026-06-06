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
    Schema::create('tracer_responses', function (Blueprint $table) {
        $table->uuid('id')->primary();
        $table->foreignUuid('student_id')->unique()->constrained('students')->onDelete('cascade');
        $table->integer('waktu_tunggu_kerja');
        $table->decimal('gaji_pertama', 15, 2)->nullable();
        $table->boolean('is_sesuai_prodi')->default(true);
        $table->text('saran_kurikulum')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tracer_responses');
    }
};
