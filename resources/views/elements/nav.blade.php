<div class="nhsuk-navigation-container">
    <nav class="nhsuk-navigation" id="header-navigation" role="navigation" aria-label="Primary navigation">

        <ul class="nhsuk-header__navigation-list">

        @if (Route::is('frameworks'))
            <li class="nhsuk-header__navigation-item nhsuk-header__navigation-item__item--current" aria-current="page">
        @else
            <li class="nhsuk-header__navigation-item">
        @endif
                <a class="nhsuk-header__navigation-link" href="{{ route('frameworks') }}">
                    {{ __('pages.frameworks.title') }}
                </a>
            </li>

        @if (Route::is('standards'))
            <li class="nhsuk-header__navigation-item nhsuk-header__navigation-item__item--current" aria-current="page">
        @else
            <li class="nhsuk-header__navigation-item">
        @endif
                <a class="nhsuk-header__navigation-link" href="{{ route('standards') }}">
                    {{ __('pages.standards.title') }}
                </a>
            </li>

{{--        @if (Route::is('competencies'))--}}
{{--            <li class="nhsuk-header__navigation-item nhsuk-header__navigation-item__item--current" aria-current="page">--}}
{{--        @else--}}
{{--            <li class="nhsuk-header__navigation-item">--}}
{{--                @endif--}}
{{--                <a class="nhsuk-header__navigation-link" href="{{ route('competencies') }}">--}}
{{--                    {{ __('pages.competencies.title') }}--}}
{{--                </a>--}}
{{--            </li>--}}

        @if (Auth::check())
            <li class="nhsuk-header__navigation-item">
                <a class="nhsuk-header__navigation-link" href="{{ url('/logout') }}">
                    {{ __('pages.generic.logout') }}
                </a>
            </li>
        @else
            <li class="nhsuk-header__navigation-item">
                <a class="nhsuk-header__navigation-link" href="{{ url('/login') }}">
                    {!! __('Sign in') !!}
                </a>
            </li>
        @endif

            <li class="nhsuk-mobile-menu-container">
                <button class="nhsuk-header__menu-toggle nhsuk-header__navigation-link" id="toggle-menu" aria-expanded="false">
                    <span class="nhsuk-u-visually-hidden">Browse </span>More
                </button>
            </li>

        </ul>

    </nav>
</div>
