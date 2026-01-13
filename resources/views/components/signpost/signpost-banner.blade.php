@props([
    'signposts' => collect(),
    'title' => 'Guidance',
])

@if ($signposts->isNotEmpty())
    <h4 class="signpost-title">{{ $title }}</h4>

    @foreach ($signposts as $sp)
        {!! $sp->guidance !!}
    @endforeach
@endif
