<div class="nhsuk-u-margin-bottom-5" wire:ignore>
    <canvas id="{{ $chart['id'] }}" style="width: 100%; max-width: 900px;"></canvas>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const chartId = @json($chart['id']);
        const chartData = @json($chart['data']);
        const chartOptions = @json($chart['options']);

        const ctx = document.getElementById(chartId);
        if (ctx) {
            new Chart(ctx, {
                type: 'bar',
                data: chartData,
                options: chartOptions,
            });
        }
    });
</script>