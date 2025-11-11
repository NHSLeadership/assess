<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    use HasFactory;

    public const string TYPE_SINGLE_CHOICE = 'single_choice';
    public const string TYPE_MULTI_CHOICE  = 'multi_choice';
    public const string TYPE_SCALE         = 'scale';
    public const string TYPE_BOOLEAN       = 'boolean';
    public const string TYPE_FREE_TEXT     = 'free_text';

    protected $fillable = [
        'node_id',
        'text',
        'slug',
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
