@props([
    'signposts' => collect(),
    'title' => 'Development resources',
])

@if ($signposts->isNotEmpty())
    <h4 class="signpost-title">{{ $title }}</h4>

    @foreach ($signposts as $sp)
        <div class="signpost-content">
            {!! $sp->guidance !!}
        </div>
    @endforeach
@endif