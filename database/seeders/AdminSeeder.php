<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
       User::create([
    'id'    => Str::uuid(), // Tambahkan ini
    'name'  => 'Administrator',
    'email' => 'admin@tracer.ac.id',
    'password' => Hash::make('password123'),
    'role'  => 'admin',
]);
    }
}