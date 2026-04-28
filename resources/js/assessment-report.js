document.addEventListener('DOMContentLoaded', function () {
    /* -----------------------------
        1. RENDER RADAR CHART
    ------------------------------ */
    const radarData = window.radarData;
    const radarOptions = window.radarOptions;

    const radarCtx = document.getElementById('radarChart');

    let radarChart = null;

    if (radarCtx) {
        const resolvedRadarCallback =
            radarOptions.useScaleLabels
                ? function (value, index) {
                    const labels = radarOptions.tickLabels || [];
                    return labels[index] ?? value;
                }
                : undefined;

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
                            callback: resolvedRadarCallback,
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
            radarChart.options.scales.r.pointLabels.font.size = 8;
            radarChart.options.scales.r.ticks.font = { size: 6 };
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
        chart.data.datasets[0].barThickness =
            window.innerWidth < 600 ? 15 : 30;

        const barCount = chart.data.labels.length;

        ctx.height = window.innerWidth < 600 ? barCount * 40 : barCount * 100;

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
                                size: window.innerWidth < 600 ? 8 : 18
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
                                size: window.innerWidth < 600 ? 8 : 16
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
                                size: window.innerWidth < 600 ? 6 : 16
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
        const DEFAULT_LABEL =
            downloadBtn.dataset.labelDefault || downloadBtn.textContent;

        downloadBtn.addEventListener('click', async () => {
            const originalText = downloadBtn.textContent;

            try {
                downloadBtn.disabled = true;
                downloadBtn.textContent = 'Generating PDF…';

                const radarCanvas = document.getElementById('radarChart');
                const radarImage = radarCanvas ? radarCanvas.toDataURL('image/png') : null;

                const barImages = [];
                (window.barCharts || []).forEach(chart => {
                    const canvas = document.getElementById(chart.id);
                    if (canvas) {
                        barImages.push({
                            id: chart.id,
                            image: canvas.toDataURL('image/png')
                        });
                    }
                });

                //Build POST payload.
                const payload = new FormData();
                payload.append('_token', window.csrfToken);
                payload.append('radarImage', radarImage || '');
                payload.append('barImages', JSON.stringify(barImages));

                //Request PDF generation from server.
                const response = await fetch(window.pdfPostUrl, {
                    method: 'POST',
                    body: payload,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) {
                    const errorText = await response.text().catch(() => '');
                    throw new Error(
                        `PDF generation failed (${response.status}). ${errorText}`.trim()
                    );
                }


                const blob = await response.blob();

                const filename = getSafeFilename(response, 'report.pdf');

                //Trigger download.
                const objectUrl = URL.createObjectURL(blob);

                const link = document.createElement('a');
                link.href = objectUrl;
                link.download = filename;
                document.body.appendChild(link);
                link.click();

                link.remove();
                URL.revokeObjectURL(objectUrl);

                downloadBtn.textContent = 'Downloaded ✓';
                setTimeout(() => {
                    downloadBtn.textContent = DEFAULT_LABEL;
                    downloadBtn.disabled = false;
                }, 1500);

            } catch (error) {
                console.error(error);
                downloadBtn.textContent = 'Download failed';
                setTimeout(() => {
                    downloadBtn.textContent = DEFAULT_LABEL;
                    downloadBtn.disabled = false;
                }, 2000);

            } finally {
                downloadBtn.disabled = false;
            }
        });
    }

    function getSafeFilename(response, fallback = 'report.pdf') {
        const header = response.headers.get('Content-Disposition');
        if (!header) return fallback;

        const starMatch = header.match(/filename\*\s*=\s*UTF-8''([^;\n]+)/i);
        if (starMatch && starMatch[1]) {
            try {
                return decodeURIComponent(starMatch[1]);
            } catch (e) {
                console.warn('Failed to decode filename* header:', e);
                return starMatch[1]; // return raw value as fallback
            }
        }

        // Fallback to plain filename="..."
        const plainMatch = header.match(/filename\s*=\s*"([^"\n]+)"/i)
            || header.match(/filename\s*=\s*([^;\n]+)/i);

        if (plainMatch && plainMatch[1]) {
            return plainMatch[1].trim();
        }

        return fallback;
    }

});
