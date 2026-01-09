<div class="nhsuk-grid-row nhsuk-u-margin-bottom-5">
    <div class="nhsuk-grid-column-full">

        @if (!empty($this->framework))
            <h1 class="nhsuk-heading-xl">
                {{ $this->framework->name ?? '' }}
            </h1>
            <h2 class="nhsuk-heading-l">Self assessment report</h2>
            <p>
                <strong>For: {{ Auth()?->user()?->name ?? '' }}</strong>
                <br>
                <strong>Academy Id: {{ Auth()?->user()?->user_id ?? '' }}</strong>
                <br>
                <strong>
                    Completed on {{ $this->assessment() ? \Carbon\Carbon::parse(data_get($this->assessment(), 'submitted_at'))->format('j F Y') : '' }}
                </strong>
            </p>
            <p>{!! data_get($this->framework, 'report_intro') ?? '' !!}</p>
        @endif



        @if (!empty($radarData))
            <div class="nhsuk-u-margin-bottom-5" wire:ignore>
                <h3>Average competency scores</h3>
                <canvas id="radarChart" style="max-height: 600px;max-width: 900px;"></canvas>
            </div>
        @endif
        @foreach ($this->nodes as $node)

            {{-- SECTION --}}
            @if (empty($node->parent))
                <h3 class="nhsuk-heading-m nhsuk-tag--no-border nhsuk-tag--{{ $node->colour ?? 'blue' }} nhsuk-u-padding-2">
                    {{ config('app.show_node_type_prefix') && $node?->type?->name ? $node->type->name . ': ' : '' }}
                    {{ $node->name ?? '' }}
                </h3>

                {{-- SECTION BAR CHART --}}
                @php
                    $chart = collect($barCharts)->firstWhere('node_id', $node->id);
                @endphp

                @if ($chart)
                    <div class="nhsuk-u-margin-bottom-5" wire:ignore>
                        <canvas id="{{ $chart['id'] }}" style="width: 100%; max-width: 900px;"></canvas>
                    </div>
                @endif

                {{-- SUBSECTION --}}
            @elseif ($node->children->count())
                <h4 class="nhsuk-heading-s">
                    {{ config('app.show_node_type_prefix') && $node?->type?->name ? $node->type->name . ': ' : '' }}
                    {{ $node->name ?? '' }}
                </h4>

                {{-- LEAF NODE (QUESTIONS) --}}
            @else
                @php
                    $nodeResponses = $this->responses?->where('question.node_id', $node->id);
                @endphp

                @if ($nodeResponses && $nodeResponses->count())
                    <ul class="nhsuk-task-list nhsuk-list--border">

                        @foreach ($nodeResponses as $response)
                            <li class="nhsuk-task-list__item nhsuk-task-list__item--with-link nhsuk-u-padding-left-2">

                                <div class="nhsuk-task-list__name-and-hint nhsuk-u-width-three-quarters">

                                    <strong>{{ $response->question->title ?? '' }}</strong>
                                    <br>

                                    {!! \App\Services\QuestionTextResolver::textFor(
                                            $this->assessment(),
                                            $this->rater(),
                                            $response->question->id
                                        ) ?? $response->question?->hint !!}

                                    @if ($response?->question?->response_type === \App\Enums\ResponseType::TYPE_TEXTAREA->value)
                                        <div class="nhsuk-task-list__hint">
                                            {{ $response->textarea ?? '' }}
                                        </div>
                                    @endif
                                </div>

                                <div class="nhsuk-task-list__status">
                                    @if ($response?->question?->response_type === \App\Enums\ResponseType::TYPE_SCALE->value)
                                        <strong class="nhsuk-tag nhsuk-tag--blue">
                                            {{ $response->scaleOption->label ?? '' }}
                                        </strong>
                                    @endif
                                </div>

                            </li>
                        @endforeach

                    </ul>
                @endif

            @endif

        @endforeach

        <div class="nhsuk-u-margin-bottom-4">
            <button id="downloadPdfBtn" class="nhsuk-button">
                Download PDF
            </button>
        </div>
    </div>

</div>

@push('scripts')
    <script>
        window.radarData = @json($radarData);
        window.radarOptions = @json($radarOptions);
        window.barCharts = @json($barCharts);
        window.csrfToken = "{{ csrf_token() }}";
        window.pdfPostUrl = "/assessment-report/{{ $frameworkId }}/{{ $assessmentId }}";
    </script>
@endpush
