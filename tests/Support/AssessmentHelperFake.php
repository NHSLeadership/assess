<?php

namespace Tests\Support;

use App\Traits\AssessmentHelperTrait;

class AssessmentHelperFake
{
    use AssessmentHelperTrait;

    public $user;

    public function __construct($user = null)
    {
        $this->user = $user;
    }

    public ?int $assessmentId = null {
        set {
            $this->assessmentId = $value;
        }
    }

}
