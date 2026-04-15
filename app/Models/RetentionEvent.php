<?php

namespace App\Models;

use App\Enums\RetentionAction;
use App\Enums\RetentionActorType;
use App\Enums\RetentionReason;
use Illuminate\Database\Eloquent\Model;

class RetentionEvent extends Model
{
    public const null UPDATED_AT = null;

    public $timestamps = false;

    protected $fillable = [
        'owner',
        'subject_type',
        'subject_id',
        'action',
        'reason',
        'actor_type',
        'actor_id',
        'metadata',
    ];

    protected $casts = [
        'action'     => RetentionAction::class,
        'reason'     => RetentionReason::class,
        'actor_type' => RetentionActorType::class,
        'created_at' => 'immutable_datetime',
        'metadata'   => 'array',
    ];
}
