<?php

use Tests\Support\AssessmentHelperFake;

test('redirectIfSubmittedOrFinished returns null when assessment is null', function () {
    $helper = new AssessmentHelperFake;

    $result = $helper->redirectIfSubmittedOrFinished(null, 1);

    expect($result)->toBeNull();
});
