<?php

namespace Tests\Support;

use App\Models\AssessmentRater;
use App\Traits\AssessmentHelperTrait;

class AssessmentHelperFake
{
    use AssessmentHelperTrait;

    public $user;

    public ?int $assessmentId = null;

    public ?int $raterId = null;

    protected ?AssessmentRater $cachedAssessmentRater = null;

    public function __construct($user = null)
    {
        $this->user = $user;
    }
}
