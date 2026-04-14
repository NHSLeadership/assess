<?php

namespace App\Enums;

enum RetentionAction: string
{
    case Warning30Days = 'warning_30_days';
    case RetentionExtended  = 'retention_extended';
    case Deleted            = 'deleted';
    case Anonymised         = 'anonymised';
}
