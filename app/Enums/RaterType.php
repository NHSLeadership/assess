<?php

declare(strict_types=1);

namespace App\Enums;

enum RaterType: string
{
    case Self = 'self';
    case Rater = 'rater';
}
