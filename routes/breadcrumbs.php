<?php
use \DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;

// Home
Breadcrumbs::for('home', function ($trail) {
    $trail->push('Home', route('home'));
});

// Home > Areas
Breadcrumbs::for('areas', function ($trail) {
    $trail->parent('frameworks');
    $trail->push('Areas', route('areas'));
});

// Home > Assessments
Breadcrumbs::for('assessments', function ($trail) {
    $trail->parent('frameworks');
    $trail->push('Assessments', route('assessments'));
});

// Home > Frameworks
Breadcrumbs::for('frameworks', function ($trail) {
    $trail->parent('stages');
    $trail->push('Frameworks', route('stages'));
});

// Home > Stages
Breadcrumbs::for('stages', function ($trail) {
    $trail->parent('home');
    $trail->push('Standard and Competencies', route('stages'));
});
