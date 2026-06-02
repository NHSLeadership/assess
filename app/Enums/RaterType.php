<?php

declare(strict_types=1);

namespace App\Enums;

enum RaterType: string
{
    case Self = 'self';
    case Manager = 'manager';
    case Report = 'report';
    case Peer = 'peer';
    case Other = 'other';
}
