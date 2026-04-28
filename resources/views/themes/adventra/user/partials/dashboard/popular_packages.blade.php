<div class="col-md-7 col-lg-7 mb-3 mb-lg-5">
    <div class="card h-100">
        <div class="card-header card-header-content-sm-between">
            <h4 class="card-header-title mb-2 mb-sm-0">@lang('Popular Packages')</h4>
            <ul class="nav nav-segment nav-fill" id="expensesTab" role="tablist">
                <li class="nav-item" data-bs-toggle="chart-bar-popular" data-datasets="totalView" data-trigger="click" data-action="toggle">
                    <a class="nav-link active" href="javascript:;" data-bs-toggle="tab">@lang('Total view')</a>
                </li>
                <li class="nav-item" data-bs-toggle="chart-bar-popular" data-datasets="totalSell" data-trigger="click" data-action="toggle">
                    <a class="nav-link" href="javascript:;" data-bs-toggle="tab">@lang('Total Sell')</a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-sm mb-2 mb-sm-0">
                    <div class="d-flex align-items-center">
                        <span class="h1 mb-0 totalShow">0</span>
                    </div>
                </div>
            </div>
            <div class="chartjs-wrapper">
                <canvas id="updatingPopularChart" class="chart-height"></canvas>
            </div>
        </div>
    </div>
</div>
@push('style')
    <style>
        .chart-height {
            width: 100%;
            max-width: 100%;
            min-height: 250px;
        }

        @media (max-width: 576px) {
            .chart-height {
                min-height: 200px;
            }
        }

        @media (max-width: 400px) {
            .chart-height {
                min-height: 180px;
            }
        }
    </style>
@endpush


@push('script')
    <script src="{{ asset(template(true).'js/chart.js') }}"></script>
    <script src="{{ asset('assets/global/js/apexcharts.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let currentDataset = 'totalView';
            let chartInstance = null;

            function fetchChartData(dataset) {
                fetch('{{ route('user.popular.packages') }}?dataset=' + dataset)
                    .then(response => response.json())
                    .then(data => {
                        document.querySelector('.totalShow').textContent = data.totalCount;
                        const labels = data.popularPackages.map(item => item.title);
                        const datasetData = data.popularPackages.map(item =>
                            dataset === 'totalView' ? item.view_count : item.total_sell
                        );

                        const ctx = document.getElementById('updatingPopularChart').getContext('2d');

                        if (chartInstance) {
                            chartInstance.destroy();
                        }

                        const isMobile = window.innerWidth < 500;

                        chartInstance = new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: labels,
                                datasets: [{
                                    label: dataset === 'totalView' ? 'Total View' : 'Total Sell',
                                    data: datasetData,
                                    backgroundColor: 'rgba(55, 125, 255, 0.3)',
                                    borderColor: '#377dff',
                                    fill: true,
                                    lineTension: 0.4,
                                    pointRadius: 4,
                                    pointBackgroundColor: '#377dff',
                                    borderWidth: 2
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: {
                                    x: {
                                        display: false,
                                        ticks: {
                                            display: false
                                        }
                                    },
                                    y: {
                                        grid: {
                                            display: true,
                                            drawBorder: false
                                        },
                                        ticks: {
                                            font: {
                                                size: isMobile ? 9 : 11
                                            }
                                        }
                                    }
                                },
                                plugins: {
                                    legend: {
                                        position: isMobile ? 'bottom' : 'top',
                                        labels: {
                                            font: {
                                                size: isMobile ? 10 : 12
                                            },
                                            boxWidth: 12
                                        }
                                    },
                                    tooltip: {
                                        callbacks: {
                                            label: function(context) {
                                                return context.formattedValue;
                                            }
                                        }
                                    }
                                }
                            }
                        });
                    });
            }

            fetchChartData(currentDataset);

            document.querySelectorAll('.nav-item').forEach(item => {
                item.addEventListener('click', function () {
                    document.querySelectorAll('.nav-item').forEach(navItem => navItem.classList.remove('active'));
                    this.classList.add('active');

                    currentDataset = this.getAttribute('data-datasets');
                    fetchChartData(currentDataset);
                });
            });
        });
    </script>
@endpush

