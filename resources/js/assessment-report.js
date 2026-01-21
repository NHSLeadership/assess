document.addEventListener('DOMContentLoaded', function () {

    const note = document.getElementById('mobilePdfNote');

    if (window.innerWidth < 768) {
        // Small screens no button, show note
        document.getElementById('downloadPdfBtn').style.display = 'none';
        note.style.display = 'block';
    } else {
        // Large screens, show button, hide note
        note.style.display = 'none';
    }

    /* -----------------------------
        1. RENDER RADAR CHART
    ------------------------------ */
    const radarData = window.radarData;
    const radarOptions = window.radarOptions;

    const radarCtx = document.getElementById('radarChart');

    let radarChart = null;

    if (radarCtx) {

        if (typeof radarOptions.callback === 'string') {
            radarOptions.callback = eval('(' + radarOptions.callback + ')');
        }

        radarChart = new Chart(radarCtx, {
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
                            callback: radarOptions.callback ?? undefined,
                            z: 10,
                        },
                        grid: {
                            color: radarOptions.gridColor,
                            z: 12,
                        },
                        angleLines: {
                            color: radarOptions.angleLineColor
                        },
                        pointLabels: {
                            font: { size: 16 },
                            color: radarOptions.pointLabelsColor
                        },
                    }
                },
                plugins: {
                    legend: {
                        labels: {
                            color: radarOptions.legendLabelsColor
                        }
                    }
                }
            }
        });

        // Mobile adjustments
        if (window.innerWidth < 600) {
            radarChart.options.scales.r.pointLabels.font.size = 10;
            radarChart.options.scales.r.ticks.font = { size: 8 };
            radarChart.update();
        }
    }


    /* -----------------------------
        2. RENDER BAR CHARTS
    ------------------------------ */
    const barCharts = window.barCharts || [];
    if (window.innerWidth < 600) {
        barCharts.forEach(chart => {
            const canvas = document.getElementById(chart.id);
            if (canvas) {
                canvas.style.height = '250px'; // adjust as needed
            }
        });
    }

    barCharts.forEach(chart => {
        const ctx = document.getElementById(chart.id);
        if (!ctx) return;
        const barCount = chart.data.labels.length;

        ctx.height = barCount * 100;

        new Chart(ctx, {
            type: 'bar',
            data: chart.data,
            options: {
                devicePixelRatio: 3,
                indexAxis: 'y',
                plugins: {
                    legend: {
                        labels: {
                            font: {
                                size: window.innerWidth < 600 ? 12 : 18
                            },
                            color: chart.options.legendLabelsColor
                        }
                    }
                },
                scales: {
                    x: {
                        min: chart.options.min,
                        max: chart.options.max,
                        ticks: {
                            color: chart.options.tickColor,
                            font: {
                                size: window.innerWidth < 600 ? 10 : 16
                            },
                            callback: function(value, index) {
                               return chart.scaleOptions[index + 1] || '';
                            },
                            autoSkip: false,

                            stepSize: 1,
                        },
                        grid: {color: chart.options.gridColor},
                    },
                    y: {
                        ticks: {
                            color: chart.options.tickColor,
                            font: {
                                size: window.innerWidth < 600 ? 10 : 16
                            },
                            callback: function(value) {

                                // wrap the label
                                const full = chart.data.labels[value];
                                const words = full.split(' ');
                                const lines = [];
                                let current = '';

                                words.forEach(word => {
                                    if ((current + word).length > 18) { // adjust 18 if needed
                                        lines.push(current.trim());
                                        current = '';
                                    }
                                    current += word + ' ';
                                });

                                if (current.trim().length) {
                                    lines.push(current.trim());
                                }

                                return lines; // renders arrays as multi-line labels
                            }
                        },
                        grid: {color: chart.options.gridColor},
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
