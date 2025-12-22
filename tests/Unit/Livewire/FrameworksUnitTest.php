<?php

use Tests\Support\FrameworksFake;
use App\Models\Assessment;
use App\Models\Framework;
use App\Models\Question;
use App\Models\Response;

test('displayAssessmentDate uses submitted_at when present', function () {
    $component = new FrameworksFake;

    $assessment = new class {
        public $submitted_at;
        public $updated_at = null;
        public $created_at = null;

        public function responses() {
            return collect([]);
        }
    };

    $assessment->submitted_at = now()->setDate(2024, 1, 1);

    $result = $component->displayAssessmentDate($assessment);

    expect($result)->toBe('1 January 2024');
});


test('displayAssessmentDate uses latest response date when not submitted', function () {
    $component = new FrameworksFake;

    // Fake response object
    $response1 = new class {
        public $updated_at;
    };
    $response1->updated_at = now()->setDate(2024, 1, 1);

    $response2 = new class {
        public $updated_at;
    };
    $response2->updated_at = now()->setDate(2024, 2, 1);

    // Fake assessment object
    $assessment = new class($response1, $response2) {
        public $submitted_at = null;
        public $updated_at = null;
        public $created_at = null;
        private $responses;

        public function __construct($r1, $r2)
        {
            $this->responses = collect([$r1, $r2]);
        }

        public function responses()
        {
            // Simulate Eloquent: orderByDesc('updated_at')->first()
            return new class($this->responses) {
                private $responses;

                public function __construct($responses)
                {
                    $this->responses = $responses;
                }

                public function orderByDesc($field)
                {
                    $sorted = $this->responses->sortByDesc($field);
                    return new class($sorted) {
                        private $sorted;
                        public function __construct($sorted) { $this->sorted = $sorted; }
                        public function first() { return $this->sorted->first(); }
                    };
                }
            };
        }
    };

    $result = $component->displayAssessmentDate($assessment);

    expect($result)->toBe('1 February 2024');
});

test('displayProgress returns Not available when assessment is null', function () {
    $component = new FrameworksFake;

    expect($component->displayProgress(null))->toBe('Not available');
});
