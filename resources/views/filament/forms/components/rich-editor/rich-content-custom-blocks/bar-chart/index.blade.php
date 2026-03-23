<div class="nhsuk-u-margin-bottom-5" wire:ignore>
    <canvas id="blockChart_{{ $chart['id'] }}" style="width:100%; max-width:900px;"></canvas>
</div>

@push('scripts')
    <script>
        window.blockCharts = window.blockCharts || [];
        window.blockCharts.push({
            id: "blockChart_{{ $chart['id'] }}",
            data: @json($chart['data']),
            options: @json($chart['options']),
        });
    </script>
@endpush