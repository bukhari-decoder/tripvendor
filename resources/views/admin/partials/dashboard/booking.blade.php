<div class="card mb-3 mb-lg-5">
    <div class="card-header card-header-content-sm-between">
        <h4 class="card-header-title mb-2 mb-sm-0">@lang('Package Booking')<i class="bi-question-circle text-body ms-1"
                                                                         data-bs-toggle="tooltip"
                                                                         data-bs-placement="top"
                                                                         aria-label="@lang('Total Package Booking History.')"
                                                                         title="@lang('Total Package Booking History.')"></i>
        </h4>
    </div>
    <div class="card-body">
        <div class="row col-lg-divider">
            <div class="col-lg-9 mb-5 mb-lg-0">
                <div class="chartjs-custom mb-4 bar-chart-height" id="PackageBooking">
                    <canvas id="PackageBChart" class="js-chart"></canvas>
                </div>
                <div class="row justify-content-center">
                    <div class="col-auto">
                        <span class="legend-indicator"></span> @lang('Booking Amount')
                    </div>
                    <div class="col-auto">
                        <span class="legend-indicator bg-primary"></span> @lang('Package Booking')
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="row">
                    <div class="col-sm-6 col-lg-12">
                        <div class="d-flex justify-content-center flex-column aside-sales-chart-height">
                            <h6 class="card-subtitle">@lang('Total Package Booking')</h6>
                            <span class="d-block display-4 text-dark mb-1 me-3">{{ $booking. ' Unit' }}</span>
                        </div>
                        <hr class="d-none d-lg-block my-0">
                    </div>

                    <div class="col-sm-6 col-lg-12">
                        <div class="d-flex justify-content-center flex-column aside-sales-chart-height">
                            <h6 class="card-subtitle">@lang('Total Booking Amount')</h6>
                            <span class="d-block display-4 text-dark mb-1 me-3">{{ currencyPosition($totalAmount) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('style')
    <style>
        .sales-chart-height {
            height: 15rem;
        }

        .aside-sales-chart-height {
            min-height: 9rem;
        }

        .bar-chart-height {
            height: 18rem;
        }
    </style>
@endpush
@push('script')
    <script>
        Notiflix.Block.standard('#PackageBooking')
        const PackageBookingChart = new Chart("PackageBChart", {
            type: "bar",
            data: {
                labels: [],
                datasets: [
                    {
                        data: [],
                        label: "Total Booking",
                        backgroundColor: "#e7eaf3",
                        hoverBackgroundColor: "#377dff",
                        borderColor: "#377dff",
                        maxBarThickness: "10"
                    },
                    {
                        data: [],
                        label: "Total Price",
                        backgroundColor: "#377dff",
                        borderColor: "#e7eaf3",
                        maxBarThickness: "10"
                    }
                ]
            },
            options: {
                maintainAspectRatio: false,
                scales: {
                    y: {
                        grid: {
                            color: "#e7eaf3",
                            drawBorder: false,
                            zeroLineColor: "#e7eaf3"
                        },
                        ticks: {
                            beginAtZero: true,
                            stepSize: 1000,
                            color: "#97a4af",
                            font: {
                                size: 12,
                                family: "Open Sans, sans-serif"
                            },
                            padding: 10
                        }
                    },
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            color: "#97a4af",
                            font: {
                                size: 12,
                                family: "Open Sans, sans-serif"
                            },
                            padding: 5
                        },
                        categoryPercentage: 0.5,
                        maxBarThickness: "10"
                    }
                },
                cornerRadius: 2,
                plugins: {
                    tooltip: {
                        hasIndicator: true,
                        mode: "index",
                        intersect: false
                    }
                },
                hover: {
                    mode: "nearest",
                    intersect: true
                }
            }
        });

        getData();
        async function getData() {
            let url = "{{ route('admin.package.booking.History') }}";
            try {
                const res = await axios.get(url);
                PackageBookingChart.data.labels = res.data.labels;
                PackageBookingChart.config.data.datasets[0].data = res.data.Unit;
                PackageBookingChart.config.data.datasets[1].data = res.data.Price;
                PackageBookingChart.update();
                Notiflix.Block.remove('#PackageBooking')
            } catch (err) {
                console.error(err);
            }
        }

        $(document).ready(function () {
            $('[data-bs-toggle="tooltip"]').tooltip();
        });
    </script>
@endpush
