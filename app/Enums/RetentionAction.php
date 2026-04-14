<?php

namespace App\Enums;

enum RetentionAction: string
{
    case Warning = 'warning';
    case RetentionExtended  = 'retention_extended';
    case Deleted            = 'deleted';
    case Anonymised         = 'anonymised';
}
