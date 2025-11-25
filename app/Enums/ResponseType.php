<?php

namespace App\Enums;
enum ResponseType: string
{
    case TYPE_SINGLE_CHOICE = 'single_choice';
    case TYPE_MULTI_CHOICE  = 'multi_choice';
    case TYPE_SCALE         = 'scale';
    case TYPE_BOOLEAN       = 'boolean';
    case TYPE_FREE_TEXT     = 'free_text';

    public function label(): string
    {
        return match($this) {
            self::TYPE_BOOLEAN       => 'boolean',
            self::TYPE_FREE_TEXT     => 'free_text',
            self::TYPE_MULTI_CHOICE  => 'multi_choice',
            self::TYPE_SCALE         => 'scale',
            self::TYPE_SINGLE_CHOICE => 'single_choice',
            default                  => 'text',
        };
    }

    public function component(): string
    {
        return match($this) {
            self::TYPE_BOOLEAN,
            self::TYPE_FREE_TEXT     => 'text',
            self::TYPE_MULTI_CHOICE  => 'checkbox',
            self::TYPE_SCALE,
            self::TYPE_SINGLE_CHOICE => 'radio',
            default => 'text',
        };
    }

    public function isNumeric(): bool
    {
        return in_array($this, [self::TYPE_SCALE, self::TYPE_SINGLE_CHOICE]);
    }
}
