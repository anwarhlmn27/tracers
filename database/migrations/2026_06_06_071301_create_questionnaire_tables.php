<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('questionnaire_forms', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->enum('target_role', ['alumni', 'atasan']);
            $table->string('angkatan')->nullable()->comment('Null jika form berlaku untuk semua angkatan');
            $table->string('form_group')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('form_questions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('form_id')->constrained('questionnaire_forms')->onDelete('cascade');
            $table->text('question_text');
            $table->string('question_type')->comment('text, textarea, radio, checkbox, select');
            $table->boolean('is_required')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('form_question_options', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('question_id')->constrained('form_questions')->onDelete('cascade');
            $table->string('option_text');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('form_responses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('form_id')->constrained('questionnaire_forms')->onDelete('cascade');
            $table->foreignUuid('user_id')->constrained('users')->onDelete('cascade')->comment('User pengisi form');
            $table->foreignUuid('evaluated_student_id')->nullable()->constrained('students')->onDelete('cascade')->comment('Diisi jika target_role adalah atasan');
            $table->timestamps();
        });

        Schema::create('form_response_answers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('response_id')->constrained('form_responses')->onDelete('cascade');
            $table->foreignUuid('question_id')->constrained('form_questions')->onDelete('cascade');
            $table->text('answer_text')->nullable(); // Menampung teks jawaban atau ID dari pilihan opsi
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('form_response_answers');
        Schema::dropIfExists('form_responses');
        Schema::dropIfExists('form_question_options');
        Schema::dropIfExists('form_questions');
        Schema::dropIfExists('questionnaire_forms');
    }
};
