@php
    if (! isset($scrollTo)) {
        $scrollTo = 'body';
    }

    $scrollIntoViewJsSnippet = ($scrollTo !== false)
        ? <<<JS
           (\$el.closest('{$scrollTo}') || document.querySelector('{$scrollTo}')).scrollIntoView()
        JS
        : '';
@endphp

@if ($paginator->hasPages())
    <nav class="nhsuk-pagination" role="navigation" aria-label="{{ __('Pagination Navigation') }}">
        <ul class="nhsuk-list nhsuk-pagination__list">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="nhsuk-pagination-item--previous disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                    <span class="nhsuk-pagination__link nhsuk-pagination__link--prev disabled">
                        <span class="nhsuk-pagination__title">{!! $previousLabel ?? __('pagination.previous') !!}</span>
                        <span class="nhsuk-u-visually-hidden">:</span>
                        <span class="nhsuk-pagination__page"></span>
                        <svg class="nhsuk-icon nhsuk-icon__arrow-left" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" width="34" height="34">
                            <path d="M4.1 12.3l2.7 3c.2.2.5.2.7 0 .1-.1.1-.2.1-.3v-2h11c.6 0 1-.4 1-1s-.4-1-1-1h-11V9c0-.2-.1-.4-.3-.5h-.2c-.1 0-.3.1-.4.2l-2.7 3c0 .2 0 .4.1.6z"></path>
                        </svg>
                    </span>
                </li>
            @else
                <li class="nhsuk-pagination-item--previous">
                    <a href="#" class="nhsuk-pagination__link nhsuk-pagination__link--prev" aria-label="@lang('pagination.previous')"
                       wire:click.prevent="previousPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled" dusk="previousPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.before">
                        <span class="nhsuk-pagination__title">{!! __('pagination.previous') !!}</span>
                        <span class="nhsuk-u-visually-hidden">:</span>
                        <span class="nhsuk-pagination__page">{!! __('pagination.page') !!} {{ $paginator->currentPage()-1 }}</span>
                        <svg class="nhsuk-icon nhsuk-icon__arrow-left" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" width="34" height="34">
                            <path d="M4.1 12.3l2.7 3c.2.2.5.2.7 0 .1-.1.1-.2.1-.3v-2h11c.6 0 1-.4 1-1s-.4-1-1-1h-11V9c0-.2-.1-.4-.3-.5h-.2c-.1 0-.3.1-.4.2l-2.7 3c0 .2 0 .4.1.6z"></path>
                        </svg>
                    </a>
                </li>
            @endif

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="nhsuk-pagination-item--next">
                    <a href="#" class="nhsuk-pagination__link nhsuk-pagination__link--next" aria-label="@lang('pagination.next')"
                        wire:click.prevent="nextPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled" dusk="nextPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.before">
                        <span class="nhsuk-pagination__title">{!! __('pagination.next') !!}</span>
                        <span class="nhsuk-u-visually-hidden">:</span>
                        <span class="nhsuk-pagination__page">{!! __('pagination.page') !!} {{ $paginator->currentPage()+1 }}</span>
                        <svg class="nhsuk-icon nhsuk-icon__arrow-right" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" width="34" height="34">
                            <path d="M19.6 11.66l-2.73-3A.51.51 0 0 0 16 9v2H5a1 1 0 0 0 0 2h11v2a.5.5 0 0 0 .32.46.39.39 0 0 0 .18 0 .52.52 0 0 0 .37-.16l2.73-3a.5.5 0 0 0 0-.64z"></path>
                        </svg>
                    </a>
                </li>
            @else
                <li class="nhsuk-pagination-item--next disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                    <span class="nhsuk-pagination__link nhsuk-pagination__link--next disabled">
                        <span class="nhsuk-pagination__title">{!! $nextLabel ?? __('pagination.next') !!}</span>
                        <span class="nhsuk-u-visually-hidden">:</span>
                        <span class="nhsuk-pagination__page"></span>
                        <svg class="nhsuk-icon nhsuk-icon__arrow-right" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" width="34" height="34">
                            <path d="M19.6 11.66l-2.73-3A.51.51 0 0 0 16 9v2H5a1 1 0 0 0 0 2h11v2a.5.5 0 0 0 .32.46.39.39 0 0 0 .18 0 .52.52 0 0 0 .37-.16l2.73-3a.5.5 0 0 0 0-.64z"></path>
                        </svg>
                    </span>
                </li>
            @endif
        </ul>

        @if (empty($this->simplePagination))
            <ul class="nhsuk-list nhsuk-pagination__pagelist">
                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <li class="nhsuk-pagination-item" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <li class="nhsuk-pagination-item active" wire:key="paginator-{{ $paginator->getPageName() }}-page{{ $page }}">
                                    <span aria-current="page" class="nhsuk-pagination__link">
                                        <span class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default leading-5">{{ $page }}</span>
                                    </span>
                                </li>
                            @else
                                <li class="nhsuk-pagination-item" wire:key="paginator-{{ $paginator->getPageName() }}-page{{ $page }}">
                                    <a href="#" class="nhsuk-pagination__link" aria-label="{{ __('Go to page :page', ['page' => $page]) }}"
                                       wire:click.prevent="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}">
                                        {{ $page }}
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </ul>
        @endif

    </nav>
@endif
