<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class QuestionnaireSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $formId = Str::uuid();

        // Buat Form Default Tracer Study
        DB::table('questionnaire_forms')->insert([
            'id' => $formId,
            'title' => 'Kuesioner Tracer Study Alumni',
            'target_role' => 'alumni',
            'angkatan' => null,
            'form_group' => 'Tracer Study (1 Year After Yudisium)',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $questions = [
            [
                'text' => 'Status Anda saat ini?',
                'type' => 'radio',
                'options' => [
                    'bekerja',
                    'wiraswasta',
                    'studi_lanjut',
                    'mencari_kerja',
                    'belum_memungkinkan'
                ]
            ],
            [
                'text' => 'Berapa bulan waktu tunggu Anda untuk mendapatkan pekerjaan pertama setelah lulus?',
                'type' => 'number',
                'options' => []
            ],
            [
                'text' => 'Apa nama perusahaan tempat Anda bekerja saat ini?',
                'type' => 'text',
                'options' => []
            ],
            [
                'text' => 'Apa jabatan Anda di perusahaan saat ini?',
                'type' => 'text',
                'options' => []
            ],
            [
                'text' => 'Apa skala tempat kerja Anda saat ini?',
                'type' => 'radio',
                'options' => [
                    'lokal',
                    'nasional',
                    'multinasional'
                ]
            ],
            [
                'text' => 'Berapa perkiraan pendapatan rata-rata per bulan? (Dalam Rupiah)',
                'type' => 'radio',
                'options' => [
                    '< 1.000.000',
                    '1.000.000 - 5.000.000',
                    '5.000.000 - 10.000.000',
                    '10.000.000 - 20.000.000',
                    '> 20.000.000',
                ]
            ],
            [
                'text' => 'Apakah pekerjaan Anda saat ini sesuai dengan program studi Anda?',
                'type' => 'radio',
                'options' => [
                    'Sangat Sesuai',
                    'Sesuai',
                    'Kurang Sesuai',
                    'Tidak Sesuai'
                ]
            ],
            [
                'text' => 'Saran atau masukan untuk perbaikan kurikulum?',
                'type' => 'textarea',
                'options' => []
            ]
        ];

        foreach ($questions as $index => $q) {
            $questionId = Str::uuid();

            DB::table('form_questions')->insert([
                'id' => $questionId,
                'form_id' => $formId,
                'question_text' => $q['text'],
                'question_type' => $q['type'],
                'is_required' => true,
                'sort_order' => $index + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            if (!empty($q['options'])) {
                foreach ($q['options'] as $optIndex => $optionText) {
                    DB::table('form_question_options')->insert([
                        'id' => Str::uuid(),
                        'question_id' => $questionId,
                        'option_text' => $optionText,
                        'sort_order' => $optIndex + 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}
