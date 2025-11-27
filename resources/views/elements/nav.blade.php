<nav class="nhsuk-header__navigation" aria-label="Menu">
    <div class="nhsuk-header__navigation-container nhsuk-width-container">

        <ul class="nhsuk-header__navigation-list">

        @if (Route::is('frameworks'))
            <li class="nhsuk-header__navigation-item nhsuk-header__navigation-item__item--current" aria-current="page">
        @else
            <li class="nhsuk-header__navigation-item">
                @endif
                <a class="nhsuk-header__navigation-link" href="{{ route('home') }}">
                    {{ __('Home') }}
                </a>
            </li>

        @if (Auth::check())
            <li class="nhsuk-header__navigation-item">
                <a class="nhsuk-header__navigation-link" href="{{ url('/admin') }}">
                    {{ __('pages.admin') }}
                </a>
            </li>
            <li class="nhsuk-header__navigation-item">
                <a class="nhsuk-header__navigation-link" href="{{ url('/logout') }}">
                    {{ __('pages.logout') }}
                </a>
            </li>
        @else
            <li class="nhsuk-header__navigation-item">
                <a class="nhsuk-header__navigation-link" href="{{ url('/login') }}">
                    {!! __('Sign in') !!}
                </a>
            </li>
        @endif

            <li class="nhsuk-header__menu" hidden>
                <button class="nhsuk-header__menu-toggle nhsuk-header__navigation-link" id="toggle-menu" aria-expanded="false">
                    <span class="nhsuk-u-visually-hidden">Browse </span>More
                </button>
            </li>

        </ul>

    </div>
</nav>
