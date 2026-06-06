<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Student;
use App\Models\Prodi;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AlumniSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $prodiTi = Prodi::firstOrCreate(
            ['kode_prodi' => 'TI'],
            ['nama_prodi' => 'Teknik Informatika']
        );

        $prodiSi = Prodi::firstOrCreate(
            ['kode_prodi' => 'SI'],
            ['nama_prodi' => 'Sistem Informasi']
        );

        $prodiEk = Prodi::firstOrCreate(
            ['kode_prodi' => 'EK'],
            ['nama_prodi' => 'Ekonomi']
        );

        $students = [
            [
                'name' => 'Budi Santoso',
                'email' => 'alumni@tracer.ac.id',
                'nim' => '12345678',
                'prodi' => $prodiTi,
                'angkatan' => 2022,
                'status' => 'lulus',
            ],
            [
                'name' => 'Siti Aminah',
                'email' => 'alumni2@tracer.ac.id',
                'nim' => '87654321',
                'prodi' => $prodiSi,
                'angkatan' => 2023,
                'status' => 'aktif',
            ],
            [
                'name' => 'Andi Wijaya',
                'email' => 'alumni3@tracer.ac.id',
                'nim' => '11223344',
                'prodi' => $prodiTi,
                'angkatan' => 2021,
                'status' => 'lulus',
            ],
            [
                'name' => 'Rina Permata',
                'email' => 'alumni4@tracer.ac.id',
                'nim' => '55667788',
                'prodi' => $prodiEk,
                'angkatan' => 2024,
                'status' => 'aktif',
            ],
        ];

        foreach ($students as $studentData) {
            $user = User::firstOrCreate(
                ['email' => $studentData['email']],
                [
                    'id' => Str::uuid(),
                    'name' => $studentData['name'],
                    'password' => Hash::make('password123'),
                    'role' => 'alumni',
                ]
            );

            Student::firstOrCreate(
                ['nim' => $studentData['nim']],
                [
                    'id' => Str::uuid(),
                    'user_id' => $user->id,
                    'prodi_id' => $studentData['prodi']->id,
                    'nama_student' => $studentData['name'],
                    'angkatan' => $studentData['angkatan'],
                    'status' => $studentData['status'],
                ]
            );
        }
    }
}
