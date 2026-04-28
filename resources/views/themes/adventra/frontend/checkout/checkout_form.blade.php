@extends(template().'layouts.app')
@section('title',trans('Checkout'))

@section('content')
    @include(template().'frontend.checkout.partials.checkout')
@endsection
@push('style')
    <link rel="stylesheet" href="{{ asset(template(true) . 'css/flatpickr.min.css') }}">
@endpush
@push('script')
    <script src="{{ asset(template(true).'js/flatpickr.min.js')}}"></script>
    <script>
        'use strict';
        document.addEventListener("DOMContentLoaded", function() {
            const editButton = document.querySelector('.edit-btn');
            const scheduleDiv = document.querySelector('.schedule');
            const dateInput = document.querySelector('.schedule-form input[name="date"]');
            const csrfToken = '{{ csrf_token() }}';

            editButton.addEventListener('click', function (event) {
                event.preventDefault();
                scheduleDiv.classList.toggle('d-none');
            });
            flatpickr('#myID', {
                enableTime: false,
                dateFormat: "Y-m-d",
                minDate: 'today',
                disableMobile: "true"
            });
            flatpickr(dateInput, {
                enableTime: false,
                dateFormat: 'Y-m-d',
                minDate: 'today',
                disableMobile: "true",
                onClose: function (selectedDates, dateStr) {
                    $.ajax({
                        url: '{{ route("user.date.update") }}',
                        type: 'POST',
                        headers: {'X-CSRF-TOKEN': csrfToken},
                        data: {
                            id: "{{ $instant->id }}",
                            date: dateStr
                        },
                        success: function (response) {
                            document.querySelector('.updated-date').textContent = dateStr;
                            const url = new URL(window.location.href);
                            url.searchParams.set('date', dateStr);
                            history.pushState({}, '', url.toString());
                        },
                        error: function (xhr) {
                            console.error(xhr.responseText);
                        }
                    });
                }
            });

            document.getElementById('checkoutForm').addEventListener('submit', function(event) {
                const totalPrice = document.getElementById('amount').value;
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'amount';
                hiddenInput.value = totalPrice;
                this.appendChild(hiddenInput);
            });

        });
        $(document).ready(function () {

            const currencySymbol = '{{ basicControl()->currency_symbol }}';

            function handleDiscountErrors(message, color) {
                $('.discountMessage').text(message).css({'color': color});
            }

            $('#grossAmount').text(currencySymbol + parseFloat($('#amount').val()).toFixed(0));

            $('#apply-coupon-btn').click(function (e) {
                e.preventDefault();
                $.ajax({
                    url: '{{ route('user.coupon.check') }}',
                    type: 'GET',
                    data: {
                        coupon: $('#coupon-input').val(),
                        instantId: "{{ $instant->id }}",
                        amount: "{{$instant->total_price}}"
                    },
                    success: function (response) {
                        if (response) {
                            const discount = response.data.discount_amount;
                            const currentTotal = response.data.total_price;
                            $('#amount').val(currentTotal);
                            $('#totalDiscount').text(currencySymbol + discount.toFixed(0));
                            handleDiscountErrors(`You got ${currencySymbol}${discount} discount`, 'green');
                            $('#grossAmount').text(currencySymbol + currentTotal.toFixed(0));
                        } else {
                            handleDiscountErrors(response.message, 'red');
                        }
                    },
                    error: function () {
                        handleDiscountErrors('The Coupon is invalid.', 'red');
                    }
                });
            });

            $('.cmn-select2').niceSelect();
            $('.crypto-select').niceSelect();
        });
    </script>
@endpush
