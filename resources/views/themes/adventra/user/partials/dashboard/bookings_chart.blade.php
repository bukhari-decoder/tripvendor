<div class="col-md-6 mb-3 mb-lg-4">
    <div class="card h-100">
        <div class="card-header card-header-content-sm-between">
            <h4 class="card-header-title mb-2 mb-sm-0">@lang('Bookings')</h4>
            <ul class="nav nav-segment nav-fill" id="expensesTab" role="tablist">
                <li class="nav-item" data-bs-toggle="chart-bar-booking" data-datasets="thisMonth" data-trigger="click" data-action="toggle">
                    <a class="nav-link active" href="javascript:;" data-bs-toggle="tab">@lang('This month')</a>
                </li>
                <li class="nav-item" data-bs-toggle="chart-bar-booking" data-datasets="lastMonth" data-trigger="click" data-action="toggle">
                    <a class="nav-link" href="javascript:;" data-bs-toggle="tab">@lang('Last month')</a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-sm mb-2 mb-sm-0">
                    <div class="d-flex align-items-center">
                        <span class="h1 mb-0 totalCount">0</span>
                        <span class="text-success ms-2">
                            <i class="bi-graph-up"></i> <span class="growthPercentageCount">0%</span>
                        </span>
                    </div>
                </div>
            </div>
            <div class="chartjs-custom " style="height: 300px;">
                <canvas id="updatingBarChart" height="300"></canvas>
            </div>
        </div>
    </div>
</div>
@push('style')
     <style>
         .chartjs-custom {
             position: relative;
             width: 100%;
         }
         #updatingBarChart {
             height: 300px !important;
             width: 100% !important;
         }
     </style>
@endpush

@push('script')
    <script src="{{ asset(template(true).'js/chart.js') }}"></script>
    <script src="{{ asset('assets/global/js/apexcharts.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function showEmptyChart() {
                const ctx = document.getElementById('updatingBarChart').getContext('2d');
                const labels = Array.from({ length: 30 }, (_, index) => `Day ${index + 1}`);
                const emptyData = Array(30).fill(0);

                const chart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'This Month',
                            data: emptyData,
                            backgroundColor: '#377dff',
                            borderColor: '#377dff',
                            maxBarThickness: 10
                        }, {
                            label: 'Last Month',
                            data: emptyData,
                            backgroundColor: '#e7eaf3',
                            borderColor: '#e7eaf3',
                            maxBarThickness: 10
                        }]
                    },
                    options: {
                        scales: {
                            x: {
                                grid: {
                                    display: false,
                                    drawBorder: false
                                },
                                ticks: {
                                    font: {
                                        size: 10
                                    }
                                }
                            },
                            y: {
                                grid: {
                                    display: true,
                                    drawBorder: false
                                },
                                ticks: {
                                    callback: function(value) {
                                        return value;
                                    },
                                    font: {
                                        size: 10
                                    }
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: true
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
            }

            fetch('{{ route('user.bookings') }}')
                .then(response => response.json())
                .then(data => {
                    const labels = data.thisMonth.map(item => 'Day ' + item.day);
                    const thisMonthData = data.thisMonth.map(item => item.booking_count);
                    const lastMonthData = data.lastMonth.map(item => item.booking_count);

                    const ctx = document.getElementById('updatingBarChart').getContext('2d');

                    const chart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [
                                {
                                    label: 'This Month',
                                    data: thisMonthData,
                                    backgroundColor: '#377dff',
                                    borderColor: '#377dff',
                                    maxBarThickness: 10
                                },
                                {
                                    label: 'Last Month',
                                    data: lastMonthData,
                                    backgroundColor: '#e7eaf3',
                                    borderColor: '#e7eaf3',
                                    maxBarThickness: 10
                                }
                            ]
                        },
                        options: {
                            scales: {
                                x: {
                                    grid: {
                                        display: false,
                                        drawBorder: false
                                    },
                                    ticks: {
                                        font: {
                                            size: 10
                                        }
                                    }
                                },
                                y: {
                                    grid: {
                                        display: true,
                                        drawBorder: false
                                    },
                                    ticks: {
                                        callback: function(value) {
                                            return value;
                                        },
                                        font: {
                                            size: 10
                                        }
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    display: true
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

                    function updateTotalsAndGrowth(isThisMonth) {
                        if (isThisMonth) {
                            chart.data.datasets[0].data = thisMonthData;
                            chart.data.datasets[1].data = lastMonthData;
                            document.querySelector('.totalCount').textContent = data.thisMonthTotal;
                        } else {
                            chart.data.datasets[0].data = lastMonthData;
                            chart.data.datasets[1].data = thisMonthData;
                            document.querySelector('.totalCount').textContent = data.lastMonthTotal;
                        }

                        if (data.lastMonthTotal === 0 && data.thisMonthTotal > 0) {
                            document.querySelector('.growthPercentageCount').textContent = (data.thisMonthTotal * 100)+'%';
                        } else {
                            document.querySelector('.growthPercentageCount').textContent = data.growthPercentage.toFixed(2) + '%';
                        }

                        chart.update();
                    }

                    document.querySelectorAll('[data-bs-toggle="chart-bar-booking"]').forEach(function(tab) {
                        tab.addEventListener('click', function() {
                            const dataset = this.getAttribute('data-datasets');
                            if (dataset === 'thisMonth') {
                                updateTotalsAndGrowth(true);
                            } else if (dataset === 'lastMonth') {
                                updateTotalsAndGrowth(false);
                            }
                        });
                    });

                    document.querySelector('.totalCount').textContent = data.thisMonthTotal;
                    updateTotalsAndGrowth(true);
                })

                .catch(error => {
                    console.error('Error loading bookings data:', error);
                    showEmptyChart();
                });
        });

    </script>
@endpush
