@props([
    'signposts' => collect(),
    'title' => 'Development resources',
])

@if ($signposts->isNotEmpty())
    <div class="nhsuk-u-display-block nhsuk-u-margin-top-4">
        <h4 class="signpost-title nhsuk-heading-xs">{{ $title }}</h4>

        @foreach ($signposts as $sp)
            <div class="signpost-content">
                {!! $sp->guidance !!}
            </div>
        @endforeach
    </div>
@endif