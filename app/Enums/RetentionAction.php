<?php

namespace App\Enums;

enum RetentionAction: string
{
    case WarningSent        = 'warning_sent';
    case RetentionExtended  = 'retention_extended';
    case Deleted            = 'deleted';
    case Anonymised         = 'anonymised';
}
