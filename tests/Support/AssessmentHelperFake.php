<?php

namespace Tests\Support;

use App\Models\AssessmentRater;
use App\Traits\AssessmentHelperTrait;

class AssessmentHelperFake
{
    use AssessmentHelperTrait;

    public $user;
    protected ?AssessmentRater $cachedAssessmentRater = null;

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
