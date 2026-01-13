<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Assessment Report</title>

    <style>
        html {
            background-color: #d8dde0;
            font-family:
                    Frutiger W01,
                    arial,
                    sans-serif;
            overflow-y: scroll;
        }
        h1, h2, h3, h4 { margin: 0 0 10px 0; }
        .section { margin-bottom: 25px; }
        .bar-chart-img, .radar-img { max-width: 100%; margin-bottom: 20px; }
        .task-list { list-style: none; padding: 0; margin: 0; }
        .task-item { padding: 10px 0; border-bottom: 1px solid #ccc; }
        .tag { display: inline-block; padding: 3px 8px; color: white; border-radius: 3px; }
        .page-break { page-break-after: always; }

        canvas {
            transform: scale(1.4);
            transform-origin: top left;
            image-rendering: crisp-edges;
        }

        /* Reserve space for header + footer */
        @page {
            margin-top: 140px;
            margin-bottom: 100px;
        }

        /* Header (repeats on every page) */
        header {
            position: fixed;
            top: -110px;
            left: 0;
            right: 0;
            height: 100px;
        }

        .answer-background {
            background-color: #ccdff1;
            color: #004281;
            border-color: #004281;
        }
    </style>
</head>

<body>

@php
    $barCharts  = $barCharts ?? [];
    $barImages  = $barImages ?? [];
    $radarImage = $radarImage ?? null;
    $responses  = collect($responses ?? []);
    $nodes      = collect($nodes ?? []);
    $framework  = $framework ?? null;
    $assessment = $assessment ?? null;
    $rater      = $rater ?? null;
@endphp

{{-- REPEATING HEADER --}}
<header>
    <img src="{{ public_path('media/nhs-logo.png') }}" style="height: 40px; width: 98px; float: right;">
</header>

@if (!empty($framework))
    <h1>{{ data_get($framework, 'name') }}</h1>
    <h2>Self assessment report</h2>
@endif

<strong>For: {{ Auth()?->user()?->name ?? '' }}</strong>
@php
if (!empty(Auth()?->user()?->user_id)) {
@endphp
    <br>
    <strong>Academy Id: {{ Auth()?->user()?->user_id ?? '' }}</strong>
@php
    }
@endphp
<br>
<strong>
    Completed on {{ $assessment ? \Carbon\Carbon::parse(data_get($assessment, 'submitted_at'))->format('j F Y') : '' }}
</strong>

<br><br>

<p>{!! data_get($framework, 'report_intro') ?? '' !!}</p>

@if (!empty($radarImage))
    <div class="page-break"></div>
    <h3>Average scores for standards</h3>
    <br>
    <div class="section">
        <img src="{{ $radarImage }}" class="radar-img" alt="Radar chart">
    </div>
@endif

@foreach ($nodes as $node)

    {{-- SECTION (top-level) --}}
    @if (empty($node->parent_id))
        <div class="page-break"></div>

        <div class="section">
            <h3 style="background: {{ $node->colour ?? '#005eb8' }}; color: white; padding: 6px;">
                {{ config('app.show_node_type_prefix') && $node?->type?->name ? $node->type->name . ': ' : '' }}
                {{ $node->name }}
            </h3>

            {{-- BAR CHART --}}
            @php
                $chart = collect($barCharts)->firstWhere('node_id', $node->id);
            @endphp

            @if ($chart && !empty(data_get($barImages, $chart['id'])))
                <img src="{{ data_get($barImages, $chart['id']) }}" class="bar-chart-img" alt="Bar chart">
            @endif
        </div>

        {{-- SUBSECTION (has children) --}}
    @elseif ($node->children && $node->children->count())
        <h4>
            {{ config('app.show_node_type_prefix') && $node?->type?->name ? $node->type->name . ': ' : '' }}
            {{ $node->name }}
        </h4>
    @endif


    {{-- ALWAYS SHOW SIGNPOSTS OR RESPONSES BELOW --}}
    @php
        $nodeSignposts = data_get($signposts, $node->id, []);
        $nodeResponses = $responses->filter(fn($r) => data_get($r, 'question.node_id') == $node->id);
    @endphp


    {{-- LEAF NODE RESPONSES --}}
    @if ($nodeResponses->count())
        <ul class="task-list">
            @foreach ($nodeResponses as $response)
                <li class="task-item">
                    <strong>{{ data_get($response, 'question.title') }}</strong><br>

                    {!! \App\Services\QuestionTextResolver::textFor(
                            $assessment,
                            $rater,
                            data_get($response, 'question.id')
                        ) ?? data_get($response, 'question.hint') !!}

                    @if (data_get($response, 'question.response_type') === \App\Enums\ResponseType::TYPE_TEXTAREA->value)
                        <div style="margin-top: 5px;">{{ data_get($response, 'textarea') }}</div>
                    @endif

                    @if (data_get($response, 'question.response_type') === \App\Enums\ResponseType::TYPE_SCALE->value)
                        <div style="margin-top: 5px;">
                            <strong class="tag answer-background">{{ Str::match('/^([A-Z][A-Za-z ]+)/', trim(data_get($response, 'scaleOption.label'))) }}</strong>
                        </div>
                    @endif
                </li>
            @endforeach
        </ul>
    @endif


    {{-- SIGNPOSTS ALWAYS SHOWN, AFTER RESPONSES IF THEY EXIST --}}
    <x-signpost-banner
            :signposts="$nodeSignposts"
            title="Guidance"
            :banner-id="$node->id"
            :pdf="true"
    />

@endforeach

{{-- REPEATING FOOTER --}}
<footer style="bottom: 0; left: 0; right: 0; height: 30px; text-align: center;">
    <script type="text/php">
        if (isset($pdf)) {
            $text = "Page {PAGE_NUM} of {PAGE_COUNT}";
            $font = $fontMetrics->get_font("Arial", "normal");
            $size = 10;
            // measure the sample so centering accounts for max width of the tokens
            $sample = "Page 100 of 100";
            $width = $fontMetrics->get_text_width($sample, $font, $size);
            $x = ($pdf->get_width() - $width) / 2;
            $y = $pdf->get_height() - 35;
            $color = array(0, 0, 0);
            $pdf->page_text($x, $y, $text, $font, $size, $color);
        }
    </script>
</footer>

</body>
</html>
