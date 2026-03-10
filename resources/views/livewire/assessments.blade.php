<div class="nhsuk-grid-row">
    <div class="nhsuk-grid-column-full">

        @if ($paginatedNodes->count())

            @foreach ($paginatedNodes as $node)
                @php
                    // Keep Livewire state aligned with the paginated node
                    $this->currentNode = $node;
                    $this->nodeId = $node->id;
                @endphp

                {{-- Heading hierarchy --}}
                @if (!empty($this->headingHierarchy()))
                    @foreach ($this->headingHierarchy() as $item)
                        <div>
                            @switch($item['headingTag'])
                                @case('h1')
                                    <h1 class="{{ $item['headingClass'] }} nhsuk-u-padding-2 nhsuk-u-display-inline-block nhsuk-u-margin-top-0 nhsuk-u-margin-bottom-0"
                                        style="background-color: {{ \App\Enums\NodeColour::from($item['colour'])?->hex() ?? 'red' }};">
                                        {{ config('app.show_node_type_prefix') && !empty($item['type']) ? $item['type'] . ': ' : '' }}
                                        {{ $item['name'] }}
                                    </h1>
                                    @break

                                @case('h2')
                                    <h2 class="{{ $item['headingClass'] }} nhsuk-u-padding-2 nhsuk-u-display-inline-block nhsuk-u-margin-top-0 nhsuk-u-margin-bottom-0"
                                        style="background-color: {{ \App\Enums\NodeColour::from($item['colour'])?->hex() ?? 'red' }};">
                                        {{ config('app.show_node_type_prefix') && !empty($item['type']) ? $item['type'] . ': ' : '' }}
                                        {{ $item['name'] }}
                                    </h2>
                                    @break

                                @case('h3')
                                    <h3 class="{{ $item['headingClass'] }} nhsuk-u-padding-2 nhsuk-u-display-inline-block nhsuk-u-margin-top-0 nhsuk-u-margin-bottom-0"
                                        style="background-color: {{ \App\Enums\NodeColour::from($item['colour'])?->hex() ?? 'red' }};">
                                        {{ config('app.show_node_type_prefix') && !empty($item['type']) ? $item['type'] . ': ' : '' }}
                                        {{ $item['name'] }}
                                    </h3>
                                    @break

                                @case('h4')
                                    <h4 class="{{ $item['headingClass'] }} nhsuk-u-padding-2 nhsuk-u-display-inline-block nhsuk-u-margin-top-0 nhsuk-u-margin-bottom-0"
                                        style="background-color: {{ \App\Enums\NodeColour::from($item['colour'])?->hex() ?? 'red' }};">
                                        {{ config('app.show_node_type_prefix') && !empty($item['type']) ? $item['type'] . ': ' : '' }}
                                        {{ $item['name'] }}
                                    </h4>
                                    @break

                                @default
                                    <h5 class="{{ $item['headingClass'] }} nhsuk-u-padding-2 nhsuk-u-display-inline-block nhsuk-u-margin-top-0 nhsuk-u-margin-bottom-0"
                                        style="background-color: {{ \App\Enums\NodeColour::from($item['colour'])?->hex() ?? 'red' }};">
                                        {{ config('app.show_node_type_prefix') && !empty($item['type']) ? $item['type'] . ': ' : '' }}
                                        {{ $item['name'] }}
                                    </h5>
                            @endswitch
                        </div>
                    @endforeach
                @endif

                {{-- Node description --}}
                @if (!empty($node->description))
                    <p>{!! $node->description !!}</p>
                @endif

                {{-- Questions --}}
                <livewire:questions
                        :assessmentId="$this->assessmentId"
                        :nodeId="$node->id"
                        :edit="$this->edit ?? null"
                        :wire:key="'questions-assessment-' . $this->assessmentId . '-node-' . $node->id"
                />

            @endforeach

            <hr class="nhsuk-u-margin-top-1">

        @else
            <p>{{ __('Assessment not found or has been removed.') }}</p>

            <div class="nhsuk-back-link">
                <a class="nhsuk-back-link__link"
                   wire:click.prevent="backPage()"
                   href="{{ route('variants') }}">
                    <svg class="nhsuk-icon nhsuk-icon__chevron-left"
                         xmlns="http://www.w3.org/2000/svg"
                         viewBox="0 0 24 24"
                         aria-hidden="true">
                        <path d="M8.5 12c0-.3.1-.5.3-.7l5-5c.4-.4 1-.4 1.4 0s.4 1 0 1.4L10.9 12l4.3 4.3c.4.4.4 1 0 1.4s-1 .4-1.4 0l-5-5c-.2-.2-.3-.4-.3-.7z"></path>
                    </svg>
                    {{ __('Step back') }}
                </a>
            </div>
        @endif

    </div>
</div>
