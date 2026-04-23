<?php

namespace App\Enums;

enum RetentionReason: string
{
    case Policy       = 'policy';
    case UserAction   = 'user_action';
    case AdminAction  = 'admin_action';
    case RtbfRequest  = 'rtbf_request';
}
