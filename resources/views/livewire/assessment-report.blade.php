<div class="nhsuk-grid-row nhsuk-u-margin-bottom-5">
    <div class="nhsuk-grid-column-full">

        @if (!empty($this->framework))
            <h1 class="nhsuk-heading-xl">
                {{ $this->framework->name ?? '' }}
            </h1>
            <h2 class="nhsuk-heading-l">Self-assessment report</h2>
            <p>
                <strong>For: {{ Auth()?->user()?->name ?? '' }}</strong>
                <br>
                <strong>Academy Id: {{ Auth()?->user()?->user_id ?? '' }}</strong>
                <br>
                <strong>
                    Completed on {{ $this->assessment() ? \Carbon\Carbon::parse(data_get($this->assessment(), 'submitted_at'))->format('j F Y') : '' }}
                </strong>
                <br>
                <strong>
                    {{ $variantAttributeLabel }}
                </strong>
            </p>
            @if(!empty(data_get($this->framework, 'report_intro')))
                <p>{!! data_get($this->framework, 'report_intro') !!}</p>
            @endif

            @if(!empty(data_get($this->framework, 'report_html')))
                <p>{!! data_get($this->framework, 'report_html') !!}</p>
            @endif

        @endif



        @if (!empty($radarData))
            <h2>Results</h2>
            <div class="nhsuk-u-margin-bottom-5" wire:ignore>
                <h3>Average scores for standards</h3>
                <canvas id="radarChart" style="width: 90%"></canvas>
            </div>
        @endif
        @foreach ($this->nodes as $node)

            {{-- SECTION (top-level) --}}
            @if (empty($node->parent))
                <div class="nhsuk-u-padding-2">
                    <h3 class="nhsuk-heading-m nhsuk-u-padding-2 nhsuk-u-display-inline-block nhsuk-u-margin-top-0 nhsuk-u-margin-bottom-0" style="background-color: {{ \App\Enums\NodeColour::from($node->colour)?->hex() ?? 'red' }};">
                        {{ config('app.show_node_type_prefix') && $node?->type?->name ? $node->type->name . ': ' : '' }}
                        {{ $node->name }}
                    </h3>
                </div>

                {{-- BAR CHART --}}
                @php
                    $chart = collect($barChartsCompetency)->firstWhere('node_id', $node->id);
                @endphp

                @if ($chart)
                    <div class="nhsuk-u-margin-bottom-5" wire:ignore>
                        <canvas id="{{ $chart['id'] }}" style="width: 100%; max-width: 900px;"></canvas>
                    </div>
                @endif

                {{-- SUBSECTION (has children) --}}
            @elseif ($node->children->count())
                <h4 class="nhsuk-heading-s">
                    {{ config('app.show_node_type_prefix') && $node?->type?->name ? $node->type->name . ': ' : '' }}
                    {{ $node->name }}
                </h4>
            @endif



            {{-- RESPONSES (leaf nodes only) --}}
            @php
                $nodeResponses = $this->responses
                    ?->filter(fn ($r) => $r->question?->node_id == $node->id);
            @endphp

            @if ($nodeResponses && $nodeResponses->count())
                <ul class="nhsuk-task-list nhsuk-list--border">

                    @foreach ($nodeResponses as $response)
                        <li class="nhsuk-task-list__item nhsuk-task-list__item--with-link nhsuk-u-padding-left-2">

                            <div class="nhsuk-task-list__name-and-hint nhsuk-u-width-three-quarters">

                                <strong>{{ $response->question->title }}</strong>
                                <br>

                                {!! \App\Services\QuestionTextResolver::textFor(
                                        $this->assessment(),
                                        $this->rater(),
                                        $response->question->id
                                    ) ?? $response->question?->hint !!}

                                @php
                                    $type = $response->question?->response_type;
                                @endphp

                                @if ($type === \App\Enums\ResponseType::TYPE_TEXTAREA->value)
                                    <div class="nhsuk-task-list__hint">
                                        {{ $response->textarea }}
                                    </div>

                                @elseif ($type === \App\Enums\ResponseType::TYPE_SCALE->value)
                                    <div class="nhsuk-task-list__hint">
                                        <strong class="nhsuk-tag nhsuk-tag--blue">
                                            {{ $response->scaleOption?->label }} {{ !empty($response->scaleOption?->description) ? ' - ' . $response->scaleOption->description : '' }}
                                        </strong>
                                        @if(!empty($response->textarea))
                                            <div class="nhsuk-u-margin-top-2">
                                                <strong>{{ __('pages.questions.reflection-label') }}:</strong>
                                                <br>
                                                {{ $response->textarea }}
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </li>
                    @endforeach

                </ul>
            @endif

            {{-- SIGNPOSTS (always show if exist) --}}
            @php
                $nodeSignposts = data_get($this->signposts, $node->id, []);
            @endphp

            <x-signpost-banner :signposts="$nodeSignposts" title="Guidance" :banner-id="$node->id" />
        @endforeach

        <div class="nhsuk-grid-row nhsuk-u-margin-bottom-5">
            <div class="nhsuk-grid-column-full nhsuk-u-margin-bottom-5">
                @if (!empty(data_get($this->framework, 'report_ending')))
                    {!! data_get($this->framework, 'report_ending') !!}
                @endif
            </div>
        </div>

        <div class="nhsuk-u-margin-bottom-4">
            <button id="downloadPdfBtn" class="nhsuk-button">
                Download PDF
            </button>
            <p id="mobilePdfNote" class="nhsuk-body" style="display:none;">
                PDF download is available on larger screens.
            </p>
        </div>
    </div>

</div>

@push('scripts')
    <script>
        window.radarData = @json($radarData);
        window.radarOptions = @json($radarOptions);
        window.barCharts = @json($barChartsCompetency);
        window.csrfToken = "{{ csrf_token() }}";
        window.pdfPostUrl = "/assessment-report/{{ $frameworkId }}/{{ $assessmentId }}";
    </script>
@endpush
