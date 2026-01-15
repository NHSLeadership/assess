<?php

namespace App\Enums;

use Filament\Support\Colors\Color;

enum NodeColour: string
{
    case GREEN = 'green';
    case PURPLE = 'purple';
    case RED = 'red';

    public function hex(): string
    {
        return match($this) {
            self::GREEN =>  '#31cc93',
            self::PURPLE => '#74aeff',
            self::RED => '#b0b0b0',
        };
    }

    public function label(): string
    {
        return match($this) {
            self::GREEN => 'Green',
            self::PURPLE => 'Purple',
            self::RED => 'Red',
        };
    }

    public function colour(): array
    {
        return match($this) {
            self::GREEN => Color::Green,
            self::PURPLE => Color::Purple,
            self::RED => Color::Red,
        };
    }

    public static function options(): array
    {
        $options = [];
        foreach (self::cases() as $case) {
            $options[$case->value] = $case->label();
        }

        return $options;
    }

    public static function colours(): array
    {
        $colours = [];
        foreach (self::cases() as $case) {
            $colours[$case->value] = $case->colour();
        }
        return $colours;
    }
}
