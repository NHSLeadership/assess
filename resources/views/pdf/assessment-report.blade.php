<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>
        @if($totalRaters == 0)
            Self-assessment report
        @else
            360 assessment report
        @endif
    </title>

    <style>
        html {
            background-color: #d8dde0;
            font-family:
                    Frutiger W01,
                    arial,
                    sans-serif;
            overflow-y: scroll;
        }
        h1 { margin: 0 0 10px 0 !important; }
        h2 { margin: 0 0 10px 0 !important; }
        h3 { margin: 0 0 10px 0 !important; }
        h4 { margin: 0 0 10px 0 !important; }

        .section { margin-bottom: 25px;}
        .align-center { text-align: center; }
        .bar-chart-img, .radar-img { max-width: 100%; margin-bottom: 50px; }

        .task-list { list-style: none; padding: 0; margin: 0; }
        .task-item { padding: 10px 0; border-bottom: 1px solid #ccc; }
        .tag { display: inline-block; padding: 3px 8px; color: white; border-radius: 3px; }
        .page-break { page-break-after: always; }


        /* Reserve space for header + footer */
        @page {
            margin-top: 90px;
            margin-bottom: 50px;
        }

        /* Header (repeats on every page) */
        header {
            position: fixed;
            top: -70px;
            height: 70px;
            float: right;
        }



        .padding-8 {
            padding: 8px;
        }

        .answer-background {
            background-color: #ccdff1;
            color: #004281;
            border-color: #004281;
        }

        .radar-img, .bar-chart-img {
            max-width: 100%;
        }

        canvas {
            transform: scale(1.4);
            transform-origin: top left;
            image-rendering: crisp-edges;
        }


        li p {
            margin: 0 !important;
            padding: 0 !important;
            display: inline; /* critical for Dompdf */
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
    <h2>
        @if($totalRaters == 0)
            Self-assessment report
        @else
            360 assessment report
        @endif
    </h2>
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
    Self-assessment completed: {{ $assessment ? \Carbon\Carbon::parse(data_get($assessment, 'submitted_at'))->format('j F Y') : '' }}
</strong>
<br>
<strong>
    {{ $variantAttributeLabel }}
</strong>
<br><br>

@if(filled(data_get($framework, 'report_intro')))
    <p>{!! data_get($framework, 'report_intro') !!}</p>
@endif

@if(filled($frameworkCustomHtml))
    <div class="page-break"></div>
    <div>{!! $frameworkCustomHtml !!}</div>
@endif


@if (!empty($radarImage))
    <div class="page-break"></div>
    <h2>Results</h2>
    <h3>Scores for all standards</h3>
    <br>
    <div class="section align-center">
        <img
                src="{{ $radarImage }}"
                class="radar-img"
                alt="Radar chart"
                @if ($isMobile) style="width: 500px; height: auto; display: block; margin: 0 auto;" @else style="max-height: 600px;max-width: 900px;" @endif
        >
    </div>
@endif

@foreach ($nodes as $node)

    {{-- SECTION (top-level) --}}
    @if (empty($node->parent_id))
        <div class="page-break"></div>

        <div class="section">
            <div class="padding-8">
                <h2 style="background-color: {{ \App\Enums\NodeColour::from($node->colour)?->hex() ?? 'red' }}; padding:8px; display: inline-block; margin-top: 0px; margin-bottom: 0px; text-align: left;">
                    {{ config('app.show_node_type_prefix') && $node?->type?->name ? $node->type->name . ': ' : '' }}
                    {{ $node->name }}
                </h2>
            </div>

            {{-- BAR CHART --}}
            @php
                $chart = collect($barCharts)->firstWhere('node_id', $node->id);
            @endphp

            @if ($chart && !empty(data_get($barImages, $chart['id'])))
                <h5>Bar chart of standards in area</h5>
                <img src="{{ data_get($barImages, $chart['id']) }}" class="bar-chart-img" alt="Bar chart">
            @endif

            @php

                $groupColumns = collect();

                $standards = $nodes
                    ->where('parent_id', $node->id);

                foreach ($standards as $standard) {

                    $standardFeedback = $raterFeedback->get($standard->id);

                    foreach (data_get($standardFeedback, 'groups', []) as $groupName => $groupData) {
                        $groupColumns->put($groupName, true);
                    }
                }

            @endphp

            @if($groupColumns->isNotEmpty())

                <h4>Group breakdown</h4>

                <table width="100%" border="1" cellspacing="0" cellpadding="5">

                    <thead>
                    <tr>
                        <th>Standard</th>

                        @foreach($groupColumns as $groupName => $unused)
                            <th>{{ $groupName }}</th>
                        @endforeach
                    </tr>
                    </thead>

                    <tbody>

                    @foreach($standards as $standard)

                        @php
                            $standardFeedback = $raterFeedback->get($standard->id);
                        @endphp

                        <tr>

                            <td>{{ $standard->name }}</td>

                            @foreach($groupColumns as $groupName => $unused)

                                @php
                                    $groupAverage = data_get(
                                        $standardFeedback,
                                        'groups.' . $groupName . '.average'
                                    );
                                @endphp

                                <td>
                                    {{ $groupAverage !== null
                                        ? $reportService->scoreLabel($groupAverage)
                                        : '—'
                                    }}
                                </td>

                            @endforeach

                        </tr>

                    @endforeach

                    </tbody>

                </table>

            @endif

        </div>

        {{-- SUBSECTION (has children) --}}
    @elseif ($node->children && $node->children->count())
        <div style="margin-top: 12px; display: block">
            <h3 style="margin-bottom: 0px; text-align: left;">
                {{ config('app.show_node_type_prefix') && $node?->type?->name ? $node->type->name . ': ' : '' }}
                {{ $node->name }}
            </h3>
        </div>
        @php
            $chart = collect($barCharts)
                ->firstWhere('node_id', $node->id);
        @endphp
        @if ($chart && !empty(data_get($barImages, $chart['id'])))
            <h4>Bar chart of competencies in standard</h4>
            <img src="{{ data_get($barImages, $chart['id']) }}" class="bar-chart-img" alt="Bar chart">
        @endif

        @php
            $feedback = $raterFeedback->get($node->id);
        @endphp
        @if($feedback && $feedback['comments']->isNotEmpty())

            <h5 class="nhsuk-heading-xs">
                360 feedback:
            </h5>

            <div class="nhsuk-u-margin-bottom-4">

                @foreach($feedback['comments'] as $comment)

                    <div class="nhsuk-body nhsuk-u-margin-bottom-2">
                        {{ $comment }}
                    </div>

                @endforeach

            </div>
        @endif
    @endif


    {{-- ALWAYS SHOW SIGNPOSTS OR RESPONSES BELOW --}}
    @php
        $nodeSignposts = data_get($signposts, $node->id, []);
        $nodeResponses = $responses->filter(fn($r) => data_get($r, 'question.node_id') == $node->id);
    @endphp

    {{-- LEAF NODE RESPONSES --}}
    @if (
        $node->children->isEmpty()
        && $nodeResponses
        && $nodeResponses->count()
    )
        <ul class="task-list">
            @foreach ($nodeResponses as $response)
                <li class="task-item">
                    <strong>{{ data_get($response, 'question.title') }}</strong><br>

                    {!! \App\Services\QuestionTextResolver::textFor(
                            $assessment,
                                $rater?->pivot,
                            data_get($response, 'question.id')
                        ) ?? data_get($response, 'question.hint') !!}

                    @if (data_get($response, 'question.response_type') === \App\Enums\ResponseType::TYPE_TEXTAREA->value)
                        <div style="margin-top: 5px;">{{ data_get($response, 'textarea') }}</div>
                    @endif
                    @if (data_get($response, 'question.response_type') === \App\Enums\ResponseType::TYPE_SCALE->value)
                        <div style="margin-top: 5px;">
                            <strong class="tag answer-background">{{ $response->scaleOption?->label }} {{ !empty($response->scaleOption?->description) ? ' - ' . $response->scaleOption->description : '' }}</strong>
                        </div>
                        @if(!empty(data_get($response, 'textarea')))
                            <div style="margin-top: 5px;">
                                <strong>{{ __('pages.summary.reflection-label') }}</strong>
                                <br>
                                {{ data_get($response, 'textarea') }}
                            </div>
                        @endif
                    @endif
                </li>
            @endforeach
        </ul>
    @endif

    {{-- SIGNPOSTS ALWAYS SHOWN, AFTER RESPONSES IF THEY EXIST --}}
    <x-signpost-banner
            :signposts="$nodeSignposts"
            title="Development resources"
            :banner-id="$node->id"
            :pdf="true"
    />

@endforeach

<section id="report-end-text">
    @if (!empty(data_get($framework, 'report_ending')))
        <div class="page-break"></div>
        {!! data_get($framework, 'report_ending') !!}
    @endif
</section>


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
