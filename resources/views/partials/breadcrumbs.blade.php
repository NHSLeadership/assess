@if (count($breadcrumbs))
    <nav class="nhsuk-breadcrumb" aria-label="Breadcrumb">
        <ol class="nhsuk-breadcrumb__list">
            @foreach ($breadcrumbs as $breadcrumb)
                @if ($breadcrumb->url && !$loop->last)
                    <li class="nhsuk-breadcrumb__list-item"><a href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a></li>
                @else
                    <li class="nhsuk-breadcrumb__list-item">{{ $breadcrumb->title }}</li>
                @endif
            @endforeach
        </ol>

        <a class="nhsuk-back-link" href="{{ $breadcrumbs->last()->url ?? route('home') }}">
            <span class="nhsuk-u-visually-hidden">Back to</span> {{ $breadcrumbs->last()->title ?? 'Home' }}
        </a>

    </nav>
@endif
