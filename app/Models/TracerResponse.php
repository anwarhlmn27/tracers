<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TracerResponse extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'tracer_responses';

    protected $fillable = [
        'student_id',
        'waktu_tunggu_kerja',
        'gaji_pertama',
        'is_sesuai_prodi',
        'saran_kurikulum',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
