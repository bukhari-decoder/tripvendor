@extends(template() . 'layouts.app')
@section('title',trans('Home'))
@section('content')
    {!!  $sectionsData !!}
@endsection

@push('script')
    <script>
        flatpickr("#datepicker", {
            dateFormat: "d-m-Y",
            minDate: "today",
        });

        $('.video-popup').magnificPopup({
            type: 'iframe',
            callbacks: {}
        });
        $(document).ready(function() {
            $('.tour-three-popup-image').on('click', function (e) {
                e.preventDefault();

                const images = JSON.parse($(this).attr('data-images'));

                $.magnificPopup.open({
                    items: images.map(img => ({ src: img })),
                    type: 'image',
                    gallery: {
                        enabled: true
                    }
                });
            });
            $('.tour-three-popup-video').magnificPopup({
                type: 'iframe'
            });
        });
    </script>
@endpush
