<?php
use Diglactic\Breadcrumbs\Breadcrumbs;

// Home
Breadcrumbs::for('home', function ($trail) {
    //$trail->push('Home', route('home'));
});

// Home > Summary
Breadcrumbs::for('summary', function ($trail) {
    $trail->parent('frameworks');
    $trail->push('Summary', route('summary'));
});

// Home > Assessments
Breadcrumbs::for('assessments', function ($trail) {
    $trail->parent('frameworks');
    $trail->push('Assessments', route('assessments'));
});

// Home > Frameworks
Breadcrumbs::for('frameworks', function ($trail) {
    $trail->parent('home');
    //$trail->push('Frameworks', route('frameworks'));
});

// Home > Stages
Breadcrumbs::for('stages', function ($trail) {
    $trail->parent('home');
    $trail->push('Standard and Competencies', route('standards'));
});
