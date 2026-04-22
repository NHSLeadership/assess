<?php

namespace App\Enums;

enum RetentionAction: string
{
    case Warn = 'warn';
    case Extend = 'extend';
    case Delete = 'delete';
    case Anonymise = 'anonymise';
}
