<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('questionnaire_forms', function (Blueprint $table) {
            $table->string('angkatan')->nullable()->after('target_role');
            $table->string('form_group')->nullable()->after('angkatan');
        });
    }

    public function down(): void
    {
        Schema::table('questionnaire_forms', function (Blueprint $table) {
            $table->dropColumn(['angkatan', 'form_group']);
        });
    }
};
