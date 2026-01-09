document.addEventListener('DOMContentLoaded', function () {

    /* -----------------------------
        1. RENDER RADAR CHART
    ------------------------------ */
    const radarData = window.radarData;
    const radarOptions = window.radarOptions;

    const radarCtx = document.getElementById('radarChart');

    if (radarCtx) {
        radarCtx.width = 900;
        radarCtx.height = 900;

        if (typeof radarOptions.callback === 'string') {
            radarOptions.callback = eval('(' + radarOptions.callback + ')');
        }
        new Chart(radarCtx, {
            type: 'radar',
            data: radarData,
            options: {
                devicePixelRatio: 2,
                scales: {
                    r: {
                        min: radarOptions.min,
                        max: radarOptions.max,
                        ticks: {
                            color: radarOptions.tickColor,
                            stepSize: 1,
                            maxTicksLimit: 100,
                            backdropColor: 'transparent',
                            showLabelBackdrop: false,
                            callback: radarOptions.callback ?? undefined
                        },
                        grid: { color: radarOptions.gridColor },
                        angleLines: { color: radarOptions.angleLineColor },
                        pointLabels: { font: { size: 16 } }
                    }
                }
            }
        });
    }

    /* -----------------------------
        2. RENDER BAR CHARTS
    ------------------------------ */
    const barCharts = window.barCharts || [];

    barCharts.forEach(chart => {
        const ctx = document.getElementById(chart.id);
        if (!ctx) return;

        new Chart(ctx, {
            type: 'bar',
            data: chart.data,
            options: {
                devicePixelRatio: 3,
                indexAxis: 'y',
                plugins: {
                    legend: { labels: { font: { size: 18 } } }
                },
                scales: {
                    x: {
                        min: chart.options.min,
                        max: chart.options.max,
                        ticks: { color: chart.options.tickColor, font: { size: 16 } },
                        grid: { color: chart.options.gridColor }
                    },
                    y: {
                        ticks: { color: chart.options.tickColor, font: { size: 16 } },
                        grid: { color: chart.options.gridColor }
                    }
                }
            }
        });
    });

    /* -----------------------------
        3. PDF DOWNLOAD HANDLER
    ------------------------------ */
    const downloadBtn = document.getElementById('downloadPdfBtn');

    if (downloadBtn) {
        downloadBtn.addEventListener('click', function () {
            downloadBtn.disabled = true;
            downloadBtn.innerText = "Generating PDF…";

            const radarCanvas = document.getElementById('radarChart');
            const radarImage = radarCanvas ? radarCanvas.toDataURL('image/png') : null;

            const barImages = [];
            barCharts.forEach(chart => {
                const canvas = document.getElementById(chart.id);
                if (canvas) {
                    barImages.push({
                        id: chart.id,
                        image: canvas.toDataURL('image/png')
                    });
                }
            });

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = window.pdfPostUrl;
            form.style.display = 'none';

            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = window.csrfToken;
            form.appendChild(csrf);

            const radarInput = document.createElement('input');
            radarInput.type = 'hidden';
            radarInput.name = 'radarImage';
            radarInput.value = radarImage;
            form.appendChild(radarInput);

            const barInput = document.createElement('input');
            barInput.type = 'hidden';
            barInput.name = 'barImages';
            barInput.value = JSON.stringify(barImages);
            form.appendChild(barInput);

            document.body.appendChild(form);
            form.submit();

            // Reset button after download starts
            setTimeout(() => {
                downloadBtn.disabled = false;
                downloadBtn.innerText = "Download PDF";
            }, 2000);
        });
    }
});
