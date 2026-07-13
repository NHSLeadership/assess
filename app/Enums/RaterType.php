<?php

declare(strict_types=1);

namespace App\Enums;

enum RaterType: string
{
    case Manager = 'manager';
    case Report = 'report';
    case Peer = 'peer';
    case Other = 'other';


    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $type) => [
                $type->value => $type->name,
            ])
            ->toArray();
    }

}
