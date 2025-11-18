<?php

namespace App\Models;

use App\Enums\ResponseType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'node_id',
        'title',
        'text',
        'hint',
        'placeholder',
        'response_type',
        'scale_id',
        'required',
        'order',
        'active',
    ];

    protected $casts = [
        'required' => 'boolean',
        'active'   => 'boolean',
    ];

    public function getComponentAttribute(): string
    {
        $type = ResponseType::tryFrom($this->attributes['response_type']);

        return $type->component() ?? 'text';
    }

    public function getNameAttribute(): string
    {
        return 'question_'.$this->attributes['id'];
    }

    public function node(): BelongsTo
    {
        return $this->belongsTo(Node::class);
    }

    public function scale(): BelongsTo
    {
        return $this->belongsTo(Scale::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(QuestionVariant::class);
    }
}
