@if (count($breadcrumbs))

    <nav class="nhsuk-breadcrumb" aria-label="Breadcrumb">
        <div class="nhsuk-width-container">
            <ol class="nhsuk-breadcrumb__list">
                @foreach ($breadcrumbs as $breadcrumb)
                    @if ($breadcrumb->url && !$loop->last)
                        <li class="nhsuk-breadcrumb__item"><a href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a></li>
                    @else
                        <li class="nhsuk-breadcrumb__item current">{{ $breadcrumb->title }}</li>
                    @endif
                @endforeach
            </ol>
            <p class="nhsuk-breadcrumb__back">
                <a class="nhsuk-breadcrumb__backlink"
                   href="https://standards-alpha.leadershipacademy.nhs.uk/">
                    <span class="nhsuk-u-visually-hidden"> Back to  &nbsp;</span> Home</a>
            </p>
        </div>
    </nav>

@endif
