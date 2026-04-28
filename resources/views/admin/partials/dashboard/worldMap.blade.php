<div id="cardFullScreenEg" class="card overflow-hidden mb-3 mb-lg-5">
    <!-- Header -->
    <div class="card-header card-header-content-between">
        <h4 class="card-header-title">@lang('Global Hotspots') <i class="bi-patch-check-fill text-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Discover the most popular travel destinations around the world, loved by travelers for their beauty, culture, and unforgettable experiences."></i></h4>
    </div>
    <div class="card-body">
        <div class="row col-sm-divider">
            <div class="col-sm-3">
                <!-- Stats -->
                <div class="d-lg-flex align-items-lg-center">
                    <div class="flex-shrink-0">
                        <i class="bi-person fs-1"></i>
                    </div>

                    <div class="flex-grow-1 ms-lg-3">
                        <span class="d-block fs-6">@lang('Users')</span>
                        <div class="d-flex align-items-center">
                            <h3 class="mb-0 userValue">0</h3>
                            <span class="badge bg-soft-success text-success ms-2">
                              <i class="bi-graph-up"></i> <span class="userPercentage">0.00%</span>
                            </span>
                        </div>
                    </div>
                </div>
                <!-- End Stats -->
            </div>

            <div class="col-sm-3">
                <!-- Stats -->
                <div class="d-lg-flex align-items-lg-center">
                    <div class="flex-shrink-0">
                        <i class="bi-clock-history fs-1"></i>
                    </div>

                    <div class="flex-grow-1 ms-lg-3">
                        <span class="d-block fs-6">@lang('Avg. Tour Booking')</span>
                        <div class="d-flex align-items-center">
                            <h3 class="mb-0 bookingValue">0</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-3">
                <!-- Stats -->
                <div class="d-lg-flex align-items-lg-center">
                    <div class="flex-shrink-0">
                        <i class="bi-files-alt fs-1"></i>
                    </div>
                    <div class="flex-grow-1 ms-lg-3">
                        <span class="d-block fs-6">@lang('Upcoming Tours')</span>
                        <div class="d-flex align-items-center">
                            <h3 class="mb-0 tourValue">0</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-3">
                <!-- Stats -->
                <div class="d-lg-flex align-items-lg-center">
                    <div class="flex-shrink-0">
                        <i class="bi-pie-chart fs-1"></i>
                    </div>

                    <div class="flex-grow-1 ms-lg-3">
                        <span class="d-block fs-6">@lang('Tour Success Rate')</span>
                        <div class="d-flex align-items-center">
                            <h3 class="mb-0 tourCompleteRate">0.0%</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <hr class="my-0">

    <div class="card-body">
        <div class="row no-gutters">
            <div class="col-lg-7">
                <div id="worldMap" class="js-jsvectormap jsvectormap-custom"></div>
            </div>

            <div class="col-lg-5">
                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-lg table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                        <thead>
                        <tr>
                            <th class="border-top-0">@lang('Destination')</th>
                            <th class="border-top-0">@lang('Package')</th>
                            <th class="border-top-0">@lang('Booking')</th>
                            <th class="border-top-0">@lang('Visitor')</th>
                        </tr>
                        </thead>

                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@push('css')
    <style>
        #worldMap {
            height: 300px;
            min-height: 300px;
        }
    </style>
@endpush

@push('script')
    <script>
        fetch('{{ route('admin.topDestinations') }}', {
            credentials: 'same-origin'
        })
            .then(response => response.json())
            .then(data => {
                new jsVectorMap({
                    selector: "#worldMap",
                    map: "world",
                    regionStyle: {
                        initial: { fill: "#bdc5d1" },
                        hover: { fill: "#77838f" }
                    },
                    markerStyle: {
                        initial: {
                            "stroke-width": 2,
                            fill: "#377dff",
                            stroke: "#fff",
                            "stroke-opacity": 1,
                            r: 6
                        },
                        hover: {
                            fill: "#377dff",
                            stroke: "#377dff"
                        }
                    },
                    markers: data.markers
                });

                document.querySelector('.userValue').textContent = data.stats.total_users;
                document.querySelector('.bookingValue').textContent = data.stats.avg_booking_per_user;
                document.querySelector('.tourValue').textContent = data.stats.upcoming_bookings;
                document.querySelector('.tourCompleteRate').textContent = data.stats.completion_rate + '%';
                document.querySelector('.userPercentage').textContent = data.stats.vendor_percentage + '% vendor';

                const destinations = data.destinations;
                const topDestinations = destinations
                    .sort((a, b) => b.packages_count - a.packages_count)
                    .slice(0, 5);


                const tbody = document.querySelector('.table tbody');

                if (!tbody) {
                    console.error('Table tbody element not found!');
                    return;
                }

                tbody.innerHTML = '';


                topDestinations.forEach(destination => {
                    const flagSrc = destination.country_flag ? destination.country_flag : './assets/vendor/flag-icon-css/flags/1x1/us.svg';

                    const row = document.createElement('tr');
                    row.innerHTML = `
                <td>
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <img class="avatar-xss avatar-circle" src="${flagSrc}" alt="${destination.country_name || destination.title}" />
                        </div>
                        <div class="flex-grow-1 ms-2">${destination.title}${destination.country_name ? ', ' + destination.country_name : ''}</div>
                    </div>
                </td>
                <td>${destination.packages_count}</td>
                <td>${destination.bookings_count}</td>
                <td>
                    <div class="d-flex align-items-center">
                        ${destination.visitor_count}
                    </div>
                </td>
            `;
                    tbody.appendChild(row);
                });
            })
            .catch(error => console.error('Map data fetch error:', error));
    </script>
@endpush


