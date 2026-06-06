<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormQuestionOption extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'question_id',
        'option_text',
        'sort_order',
    ];

    public function question(): BelongsTo
    {
        return $this->belongsTo(FormQuestion::class, 'question_id');
    }
}
