<nav class="nhsuk-header__navigation" aria-label="Menu">
    <div class="nhsuk-header__navigation-container nhsuk-width-container">

        <ul class="nhsuk-header__navigation-list">

            @if (Route::is('home'))
                <li class="nhsuk-header__navigation-item nhsuk-header__navigation-item__item--current" aria-current="page">
            @else
                <li class="nhsuk-header__navigation-item">
                    @endif
                    <a class="nhsuk-header__navigation-link" href="{{ route('home') }}">
                        {{ __('pages.home.title') }}
                    </a>
                </li>

                <li class="nhsuk-header__menu" hidden>
                    <button class="nhsuk-header__menu-toggle nhsuk-header__navigation-link" id="toggle-menu" aria-expanded="false">
                        <span class="nhsuk-u-visually-hidden">Browse </span>More
                    </button>
                </li>

        </ul>

    </div>
</nav>