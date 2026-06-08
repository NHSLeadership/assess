<?php

declare(strict_types=1);

namespace App\Enums;

enum Audience: string
{
    case Self = 'self';
    case Rater = 'rater';
}
