<?php

namespace Tests\Support;

use App\Traits\AssessmentHelperTrait;

class AssessmentHelperFake
{
    use AssessmentHelperTrait;

    public ?int $assessmentId = null {
        set {
            $this->assessmentId = $value;
        }
    }

    // Fake user() from UserTrait
    public function user()
    {
        return (object) ['user_id' => 1];
    }
}
