<div class="nhsuk-navigation-container">
    <nav class="nhsuk-navigation" id="header-navigation" role="navigation" aria-label="Primary navigation">

        <ul class="nhsuk-header__navigation-list">

        @if (Route::is('stages'))
            <li class="nhsuk-header__navigation-item nhsuk-header__navigation-item__item--current" aria-current="page">
        @else
            <li class="nhsuk-header__navigation-item">
                @endif
                <a class="nhsuk-header__navigation-link" href="{{ route('stages') }}">
                    {{ __('pages.stages.title') }}
                </a>
            </li>

        @if (Route::is('frameworks'))
            <li class="nhsuk-header__navigation-item nhsuk-header__navigation-item__item--current" aria-current="page">
        @else
            <li class="nhsuk-header__navigation-item">
        @endif
                <a class="nhsuk-header__navigation-link" href="{{ route('frameworks') }}">
                    {{ __('pages.frameworks.title') }}
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
                    <svg class="nhsuk-icon nhsuk-icon__chevron-down" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                        <path d="M15.5 12a1 1 0 0 1-.29.71l-5 5a1 1 0 0 1-1.42-1.42l4.3-4.29-4.3-4.29a1 1 0 0 1 1.42-1.42l5 5a1 1 0 0 1 .29.71z"></path>
                    </svg>
                </button>
            </li>

        </ul>
    </nav>
</div>
