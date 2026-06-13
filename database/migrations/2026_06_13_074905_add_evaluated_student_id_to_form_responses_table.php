<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('form_responses', function (Blueprint $table) {
            $table->foreignUuid('evaluated_student_id')->nullable()->after('user_id')->constrained('students')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('form_responses', function (Blueprint $table) {
            $table->dropForeign(['evaluated_student_id']);
            $table->dropColumn('evaluated_student_id');
        });
    }
};
