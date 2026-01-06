<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Assessment Report</title>
    <style>
        body { font-family: sans-serif; }
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
        @page {
            margin-top: 40px;
            margin-bottom: 80px;
        }

        .radar-img {
            height: auto;          /* scales proportionally */
            width: auto;        /* keeps correct aspect ratio */
            display: block;
            margin: 0 auto 40px;
        }

        html {
            counter-reset: page;
        }

        footer {
            bottom: 0;
            left: 0;
            right: 0;
            height: 30px;
            text-align: center;
        }

        #footer-bar {
            background: #005eb8;
            height: 6px;
            width: 100%;
        }

        #footer-content {
            text-align: center;
            font-size: 12px;
            color: #333;
            margin-top: 5px;
        }

        .answer-background {
            background-color: #ccdff1;
            color: #004281;
            border-color: #004281;
        }
    </style>
</head>
<body>
{{-- NHS PDF HEADER --}}
<header style="background:#005eb8; padding:20px; margin-bottom:30px;">
    <div style="display:flex; align-items:center;">
        <img src="{{ public_path('media/nhs-logo.png') }}" width="100" height="40" alt="NHS Logo">

        <span style="font-size:22px; font-weight:bold; color:white; margin-left:15px;">
            {{ config('app.name') ?? 'Self Assessment Tools' }}
        </span>
    </div>

</header>
{{-- HEADER --}}
@if (!empty($framework))
    <h1>{{ $framework->name }}</h1>
    <h2>Assessment Report</h2>
@endif

{{-- RADAR CHART --}}
@if (!empty($radarImage))
    <div class="section">
        <img src="{{ $radarImage }}" class="radar-img" >
    </div>
@endif
<div class="page-break"></div>

{{-- NODES --}}
@foreach ($nodes as $node)

    {{-- AREA (top-level section) --}}
    @if (empty($node->parent_id))
        <div class="section">
            <h3 style="background: {{ $node->colour ?? '#005eb8' }}; color: white; padding: 6px;">
                {{ config('app.show_node_type_prefix') && $node?->type?->name ? $node->type->name . ': ' : '' }}
                {{ $node->name }}
            </h3>

            {{-- BAR CHART FOR THIS AREA --}}
            @php
                $chart = collect($barCharts)->firstWhere('node_id', $node->id);
            @endphp

            @if ($chart && !empty($barImages[$chart['id']]))
                <img src="{{ $barImages[$chart['id']] }}" class="bar-chart-img">
            @endif
        </div>

        {{-- SUBSECTION --}}
    @elseif ($node->children->count())
        <h4>
            {{ config('app.show_node_type_prefix') && $node?->type?->name ? $node->type->name . ': ' : '' }}
            {{ $node->name }}
        </h4>

        {{-- LEAF NODE (QUESTIONS) --}}
    @else
        @php
            $nodeResponses = $responses?->where('question.node_id', $node->id);
        @endphp

        @if ($nodeResponses && $nodeResponses->count())
            <ul class="task-list">

                @foreach ($nodeResponses as $response)
                    <li class="task-item">

                        <strong>{{ $response->question->title }}</strong><br>

                        {{-- Question text --}}
                        {!! \App\Services\QuestionTextResolver::textFor(
                                $assessment,
                                $rater,
                                $response->question->id
                            ) ?? $response->question?->hint !!}

                        {{-- Textarea response --}}
                        @if ($response->question->response_type === \App\Enums\ResponseType::TYPE_TEXTAREA->value)
                            <div style="margin-top: 5px;">
                                {{ $response->textarea }}
                            </div>
                        @endif

                        {{-- Scale response --}}
                        @if ($response->question->response_type === \App\Enums\ResponseType::TYPE_SCALE->value)
                            <div style="margin-top: 5px;">
                                <strong class="tag answer-background">{{ $response->scaleOption->label }}</strong>
                            </div>
                        @endif

                    </li>
                @endforeach
            </ul>
        @endif
    @endif
@endforeach

<footer>
    <script type="text/php">
        if (isset($pdf)) {
            echo '<div id="footer-bar">d</div>';
            $font = $fontMetrics->get_font("sans-serif", "normal");
            $pdf->page_text(270, 820, "Page {PAGE_NUM} of {PAGE_COUNT}", $font, 10);
        }
    </script>
</footer>

</body>
</html>
