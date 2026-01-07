<?php

namespace App\Enums;

enum NodeVisibility: string
{
    case Always = 'always';
    case Never  = 'never';
    case Self   = 'self';
    case Rater  = 'rater';

    public function label(): string
    {
        return match ($this) {
            self::Always => 'Always show',
            self::Never  => 'Never show',
            self::Self   => 'Show to self raters only',
            self::Rater  => 'Show to external raters only',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $case) => [$case->value => $case->label()])
            ->all();
    }
}
