<nav class="nhsuk-header__navigation" aria-label="Menu">
    <div class="nhsuk-header__navigation-container nhsuk-width-container">

        <ul class="nhsuk-header__navigation-list">

        @if (Route::is('home'))
            <li class="nhsuk-header__navigation-item nhsuk-header__navigation-item__item--current" aria-current="page">
        @else
            <li class="nhsuk-header__navigation-item">
        @endif
                <a class="nhsuk-header__navigation-link" href="{{ route('assessments') }}">
                    {{ __('pages.assessments.title') }}
                </a>
            </li>

{{--        @if (Route::is('forms'))--}}
{{--            <li class="nhsuk-header__navigation-item nhsuk-header__navigation-item__item--current" aria-current="page">--}}
{{--        @else--}}
{{--            <li class="nhsuk-header__navigation-item">--}}
{{--                @endif--}}
{{--                <a class="nhsuk-header__navigation-link" href="{{ route('forms') }}">--}}
{{--                    {{ __('pages.forms.title') }}--}}
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
    </div>
</nav>
