<style>
    .report img {
        max-width: 100%;
        height: auto;
    }

    .report ul {
        padding-left: 3rem;
    }

    /* Second-level bullets → hollow circles */
    .report ul ul {
        list-style-type: circle;
    }

    /* ===== Grid container ===== */
    .grid-layout {
        /* Use provided CSS var from your inline style, with a safe fallback */
        --cols: repeat(3, minmax(0, 1fr));
        --gap: 1.25rem; /* 20px */
        display: grid;
        grid-template-columns: 1fr; /* mobile first: one column */
        gap: var(--gap);
        align-items: stretch;
    }

    /* Scale up to the provided breakpoint only when asked */
    @media (min-width: 1024px) {
        .grid-layout[data-from-breakpoint="lg"] {
            grid-template-columns: var(--cols);
        }
    }

    /* ===== Cards ===== */
    .grid-layout-col {
        /* help the card fill the available height in some browsers */
        height: 100%;
        display: flex;          /* optional: allows inner content to flow nicely */
        flex-direction: column; /* optional */

        /* Allow card to span as per inline style var (with fallback) */
        --col-span: span 1 / span 1;
        grid-column: var(--col-span);

        background: #bddecd;          /* green as default (col 1) */
        border: 1px solid #d3e3d8;     /* subtle border */
        border-radius: 16px;
        padding: 1.25rem 1.25rem 1.5rem; /* space for content and rounded look */
        box-shadow:
                0 1px 0 rgba(0,0,0,0.03),
                0 6px 18px rgba(0,0,0,0.06);  /* soft depth */
    }

    /* Alternate background colours by column (match your 3-card palette) */
    .grid-layout-col:nth-child(1) {
        background: #bddecd;  /* green */
        border-color: #cfe3d6;
    }
    .grid-layout-col:nth-child(2) {
        background: #decad4;  /* red */
        border-color: #edd7b6;
    }
    .grid-layout-col:nth-child(3) {
        background: #bccae0;  /* purple */
        border-color: #cbd3e6;
    }

    /* ===== Headings ===== */
    .grid-layout-col > h3 {
        margin: 0 0 0.75rem 0;
        text-align: center;
        font-weight: 800;
        font-size: 1.35rem;
        line-height: 1.2;
    }

    /* Section subheads (the <strong> lines) */
    .grid-layout-col p > strong {
        display: block;
        margin-top: 0.75rem;
        margin-bottom: 0.25rem;
        font-weight: 800;
    }

    /* ===== Lists ===== */
    .grid-layout-col ul {
        margin: 0 0 0.75rem 1.2rem;  /* indent bullets slightly */
        padding: 0;
        list-style: disc;
    }

    .grid-layout-col ul li {
        margin: 0.25rem 0;          /* compact vertical rhythm */
    }

    /* Remove extra <p> spacing inside list items  */
    .grid-layout-col ul li p {
        margin: 0;
    }

    /* Paragraph spacing for top-level <p> */
    .grid-layout-col > p {
        margin: 0 0 0.25rem 0;
    }

    /* ===== Nice “pill” corners visually ===== */
    .grid-layout-col {
        /* Make the rounded corners feel prominent by clipping overflow */
        overflow: hidden;
    }

    /* ===== Optional: consistent typography scale ===== */
    .grid-layout,
    .grid-layout * {
        /* Keep lists and paragraphs readable */
        line-height: 1.45;
    }
</style>
<div class="nhsuk-grid-row nhsuk-u-margin-bottom-5 report">
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

        <h2>Results</h2>

        @if (!empty($radarData))
            <div class="nhsuk-u-margin-bottom-5" wire:ignore>
                <h3>Average scores for standards</h3>
                <canvas id="radarChart" style="max-height: 600px;max-width: 900px;"></canvas>
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

        <p>{!! data_get($this->framework, 'report_ending') ?? '' !!}</p>

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
