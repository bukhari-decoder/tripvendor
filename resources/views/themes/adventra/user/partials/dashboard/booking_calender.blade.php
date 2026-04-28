<div class="col-md-6 col-lg-6 mb-3 mb-lg-4">
    <div class="card h-100">
        <div class="card-header card-header-content-sm-between">
            <h4 class="card-header-title mb-2 mb-sm-0">@lang('Booking Calender')</h4>

        </div>
        <div class="card-body">
            <div id="calendar"></div>
        </div>
    </div>
</div>
@push('style')
    <link href="{{ asset(template(true).'css/fullcalendar.min.css') }}" rel="stylesheet">
    <style>
        .tooltip {
            position: absolute;
            background-color: #fff;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 5px;
            font-size: 12px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
            max-width: 250px;
            z-index: 9999;
            pointer-events: none;
        }

        #calendar {
            width: 100%;
            min-height: 300px;
        }

        @media (max-width: 768px) {
            #calendar {
                min-height: 250px;
            }
        }

        @media (max-width: 480px) {
            #calendar {
                min-height: 200px;
            }
        }
    </style>
@endpush

@push('script')
    <script src="{{ asset(template(true).'js/moment.min.js') }}"></script>
    <script src="{{ asset(template(true).'js/fullcalendar.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            function getInitialView() {
                if (window.innerWidth <= 480) return 'listWeek';
                if (window.innerWidth <= 768) return 'basicWeek';
                return 'month';
            }

            $('#calendar').fullCalendar({
                height: 'auto',
                contentHeight: 'auto',
                aspectRatio: 1.5,
                defaultView: getInitialView(),
                windowResize: function (view) {
                    var newView = getInitialView();
                    $('#calendar').fullCalendar('changeView', newView);
                },
                events: function (start, end, timezone, callback) {
                    var vendor_id = "{{ auth()->id() }}";
                    var month = moment().format('YYYY-MM');

                    $.ajax({
                        url: '{{ route('user.booking.calender') }}',
                        data: {
                            vendor_id: vendor_id,
                            month: month
                        },
                        success: function (data) {
                            var events = data.map(function (booking) {
                                return {
                                    title: booking.booking_count + ' bookings',
                                    start: booking.date,
                                    total_person: booking.total_person_date,
                                    booking_details: booking
                                };
                            });
                            callback(events);
                        }
                    });
                },
                editable: false,
                droppable: false,
                eventRender: function (event, element) {
                    var additionalInfo = '<br><small>' + event.total_person + ' persons</small>';
                    element.find('.fc-title').append(additionalInfo);
                },
                eventMouseEnter: function (event, jsEvent, view) {
                    var content = "<strong>Date:</strong> " + event.start.format('YYYY-MM-DD') + "<br>" +
                        "<strong>Bookings:</strong> " + event.booking_details.booking_count + "<br>" +
                        "<strong>Persons:</strong> " + event.booking_details.total_person_date;

                    var tooltip = $('<div class="tooltip">' + content + '</div>');
                    $('body').append(tooltip);

                    $(this).mousemove(function (e) {
                        tooltip.css({
                            top: e.pageY + 5,
                            left: e.pageX + 5
                        });
                    });

                    $(this).on('mouseleave', function () {
                        tooltip.remove();
                    });
                },
                eventMouseLeave: function () {
                    $('div.tooltip').remove();
                }
            });
        });
    </script>
@endpush


