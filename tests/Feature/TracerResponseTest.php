<?php

namespace Tests\Feature;

use App\Models\Prodi;
use App\Models\Student;
use App\Models\User;
use App\Models\TracerResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TracerResponseTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_form(): void
    {
        $response = $this->get('/form');
        $response->assertRedirect('/');
    }

    public function test_user_without_student_record_sees_warning(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($user)->get('/form');

        $response->assertStatus(200);
        $response->assertViewHas('student', null);
        $response->assertSee('Maaf, data mahasiswa untuk akun ini belum terdaftar');
    }

    public function test_user_with_student_record_can_view_form(): void
    {
        $user = User::factory()->create(['role' => 'alumni']);
        $prodi = Prodi::create([
            'kode_prodi' => 'TI',
            'nama_prodi' => 'Teknik Informatika'
        ]);
        $student = Student::create([
            'user_id' => $user->id,
            'prodi_id' => $prodi->id,
            'nim' => '12345678',
            'nama_student' => 'Test Student',
            'angkatan' => 2022,
            'status' => 'lulus',
        ]);

        $response = $this->actingAs($user)->get('/form');

        $response->assertStatus(200);
        $response->assertViewHas('student');
        $response->assertDontSee('Maaf, data mahasiswa untuk akun ini belum terdaftar');
        $response->assertSee('Daftar Tracer Response');
        $response->assertSee('Belum ada data tracer response');
    }

    public function test_student_can_submit_valid_tracer_response(): void
    {
        $user = User::factory()->create(['role' => 'alumni']);
        $prodi = Prodi::create([
            'kode_prodi' => 'TI',
            'nama_prodi' => 'Teknik Informatika'
        ]);
        $student = Student::create([
            'user_id' => $user->id,
            'prodi_id' => $prodi->id,
            'nim' => '12345678',
            'nama_student' => 'Test Student',
            'angkatan' => 2022,
            'status' => 'lulus',
        ]);

        $response = $this->actingAs($user)->post('/form', [
            'waktu_tunggu_kerja' => 3,
            'gaji_pertama' => 5000000,
            'is_sesuai_prodi' => 1,
            'saran_kurikulum' => 'Lebih banyak praktek coding.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('tracer_responses', [
            'student_id' => $student->id,
            'waktu_tunggu_kerja' => 3,
            'gaji_pertama' => 5000000.00,
            'is_sesuai_prodi' => true,
            'saran_kurikulum' => 'Lebih banyak praktek coding.',
        ]);
    }

    public function test_student_cannot_submit_duplicate_tracer_response(): void
    {
        $user = User::factory()->create(['role' => 'alumni']);
        $prodi = Prodi::create([
            'kode_prodi' => 'TI',
            'nama_prodi' => 'Teknik Informatika'
        ]);
        $student = Student::create([
            'user_id' => $user->id,
            'prodi_id' => $prodi->id,
            'nim' => '12345678',
            'nama_student' => 'Test Student',
            'angkatan' => 2022,
            'status' => 'lulus',
        ]);

        // Create first response
        TracerResponse::create([
            'student_id' => $student->id,
            'waktu_tunggu_kerja' => 3,
            'gaji_pertama' => 5000000,
            'is_sesuai_prodi' => true,
            'saran_kurikulum' => 'Lebih banyak praktek coding.',
        ]);

        // Try to submit second response
        $response = $this->actingAs($user)->post('/form', [
            'waktu_tunggu_kerja' => 6,
            'gaji_pertama' => 6000000,
            'is_sesuai_prodi' => false,
            'saran_kurikulum' => 'Saran baru.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Anda sudah mengisi form tracer study.');
        $this->assertEquals(1, TracerResponse::where('student_id', $student->id)->count());
    }

    public function test_student_can_delete_tracer_response(): void
    {
        $user = User::factory()->create(['role' => 'alumni']);
        $prodi = Prodi::create([
            'kode_prodi' => 'TI',
            'nama_prodi' => 'Teknik Informatika'
        ]);
        $student = Student::create([
            'user_id' => $user->id,
            'prodi_id' => $prodi->id,
            'nim' => '12345678',
            'nama_student' => 'Test Student',
            'angkatan' => 2022,
            'status' => 'lulus',
        ]);

        $tracerResponse = TracerResponse::create([
            'student_id' => $student->id,
            'waktu_tunggu_kerja' => 3,
            'gaji_pertama' => 5000000,
            'is_sesuai_prodi' => true,
            'saran_kurikulum' => 'Bagus.',
        ]);

        $response = $this->actingAs($user)->delete("/form/{$tracerResponse->id}");

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Tracer response berhasil dihapus!');
        $this->assertDatabaseMissing('tracer_responses', ['id' => $tracerResponse->id]);
    }

    public function test_unauthorized_user_cannot_delete_tracer_response(): void
    {
        $user1 = User::factory()->create(['role' => 'alumni']);
        $user2 = User::factory()->create(['role' => 'alumni']);
        $prodi = Prodi::create([
            'kode_prodi' => 'TI',
            'nama_prodi' => 'Teknik Informatika'
        ]);
        $student1 = Student::create([
            'user_id' => $user1->id,
            'prodi_id' => $prodi->id,
            'nim' => '12345678',
            'nama_student' => 'Test Student 1',
            'angkatan' => 2022,
            'status' => 'lulus',
        ]);
        $student2 = Student::create([
            'user_id' => $user2->id,
            'prodi_id' => $prodi->id,
            'nim' => '87654321',
            'nama_student' => 'Test Student 2',
            'angkatan' => 2022,
            'status' => 'lulus',
        ]);

        $tracerResponse = TracerResponse::create([
            'student_id' => $student1->id,
            'waktu_tunggu_kerja' => 3,
            'gaji_pertama' => 5000000,
            'is_sesuai_prodi' => true,
            'saran_kurikulum' => 'Bagus.',
        ]);

        $response = $this->actingAs($user2)->delete("/form/{$tracerResponse->id}");

        $response->assertStatus(404);
        $this->assertDatabaseHas('tracer_responses', ['id' => $tracerResponse->id]);
    }
}
