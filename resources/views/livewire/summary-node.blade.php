{{-- Heading --}}
@if ($level === 0)
    <h3 class="nhsuk-heading-m nhsuk-u-padding-2"
        style="background-color: {{ \App\Enums\NodeColour::from($node->colour)?->hex() }}">
        {{ config('app.show_node_type_prefix') && $node->type?->name ? $node->type->name . ': ' : '' }}
        {{ $node->name }}
    </h3>
@else
    <h4 class="nhsuk-heading-s">
        {{ config('app.show_node_type_prefix') && $node->type?->name ? $node->type->name . ': ' : '' }}
        {{ $node->name }}
    </h4>
@endif

{{-- Questions --}}
@if ($node->questions->count())
    <ul class="nhsuk-task-list nhsuk-list--border">
        @foreach ($node->questions as $question)
            @php
                $response = $responses?->firstWhere('question_id', $question->id);
            @endphp

            <li class="nhsuk-task-list__item nhsuk-task-list__item--with-link nhsuk-u-padding-left-2">
                <div class="nhsuk-task-list__name-and-hint nhsuk-u-width-three-quarters">

                    {{-- Title --}}
                    @if ($assessment?->submitted_at)
                        <strong>{!! $question->title !!}</strong>
                    @else
                        <a href="#"
                           wire:click.prevent="editAnswer({{ $question->node_id }})"
                           class="nhsuk-link nhsuk-task-list__link">
                            <strong>{!! $question->title !!}</strong>
                        </a>
                        <span class="nhsuk-u-visually-hidden">Click to edit this answer</span>
                    @endif

                    <br>

                    {{-- Question text --}}
                    {!! \App\Services\QuestionTextResolver::textFor(
                        $assessment,
                        $rater,
                        $question->id
                    ) !!}

                    {{-- Response --}}
                    @if ($response)
                        @php $type = $question->response_type; @endphp

                        @if ($type === \App\Enums\ResponseType::TYPE_TEXTAREA->value)
                            <div class="nhsuk-task-list__hint">
                                {{ $response->textarea }}
                            </div>
                        @elseif ($type === \App\Enums\ResponseType::TYPE_SCALE->value)
                            <div class="nhsuk-task-list__hint">
                                <strong class="nhsuk-tag nhsuk-tag--blue">
                                    {{ $response->scaleOption->label }}
                                    {{ $response->scaleOption->description
                                        ? ' - ' . $response->scaleOption->description
                                        : '' }}
                                </strong>
                            </div>
                        @endif
                    @else
                        <div class="nhsuk-task-list__hint nhsuk-u-secondary-text-color">
                            <em>No answer provided</em>
                        </div>
                    @endif

                </div>
            </li>
        @endforeach
    </ul>
@endif

{{-- Children --}}
@foreach ($node->children as $child)
    @include('livewire.summary-node', [
        'node'       => $child,
        'level'      => $level + 1,
        'responses'  => $responses,
        'assessment' => $assessment,
        'rater'      => $rater,
    ])
@endforeach
