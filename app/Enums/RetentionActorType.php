<?php

namespace App\Enums;

enum RetentionActorType: string
{
    case System = 'system';
    case User   = 'user';
    case Admin  = 'admin';
}
