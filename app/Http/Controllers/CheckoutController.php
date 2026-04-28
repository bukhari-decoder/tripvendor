<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Coupon;
use App\Models\Deposit;
use App\Models\Gateway;
use App\Models\InstantSave;
use App\Models\Package;
use App\Models\Page;
use App\Models\Transaction;
use App\Models\UserGateway;
use App\Traits\Notify;
use App\Traits\PaymentValidationCheck;
use App\Traits\Upload;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;
use PhpParser\Node\Expr\New_;

class CheckoutController extends Controller
{
    use Upload, Notify, PaymentValidationCheck;

    public function checkoutForm(Request $request, $slug, $uid = null)
    {
        try {
            $seoData = Page::where('name', 'packages')->select(['page_title', 'meta_title', 'meta_keywords', 'meta_description', 'og_description', 'meta_robots', 'meta_image_driver', 'meta_image', 'breadcrumb_status', 'breadcrumb_image', 'breadcrumb_image_driver'])->first();

            $data['pageSeo'] = [
                'page_title' => 'Package Details',
                'meta_title' => $seoData->meta_title,
                'meta_keywords' => implode(',', $seoData->meta_keywords ?? []),
                'meta_description' => $seoData->meta_description,
                'og_description' => $seoData->og_description,
                'meta_robots' => $seoData->meta_robots,
                'meta_image' => $seoData
                    ? getFile($seoData->meta_image_driver, $seoData->meta_image)
                    : null,
                'breadcrumb_status' => $seoData->breadcrumb_status ?? null,
                'breadcrumb_image' => $seoData->breadcrumb_status
                    ? getFile($seoData->breadcrumb_image_driver, $seoData->breadcrumb_image)
                    : null,
            ];

            $user = Auth::user();
            $data['package'] = Package::with('category:id,name')->where('slug', $slug)->firstOr(function () {
                throw new \Exception('The package was not found.');
            });
            if ($data['package']->owner_id == auth()->id()) {
                return back()->with('error', 'You are not allowed to purchase your own packages.');
            }
            $instant = null;
            if ($uid && $data['package']) {
                $instant = Booking::where('uid', $uid)->where('status', 0)->firstOr(function () {
                    throw new \Exception('The booking record was not found.');
                });
            }

            $data['spaceAttribute'] = $data['package']->getBookingsSpaceAttribute($data['package']->id);

            $caldiscountAmount = 0;

            if ($uid == null) {
                if (!isset($request->date)) {
                    return back()->with('error', 'The date field is required.');
                }
            }

            if (isset($request->date) && isset($request->totalInfant) && isset($request->totalChildren) && isset($request->totalAdult)) {
                $totalPerson = $request->totalAdult + $request->totalInfant + $request->totalChildren;
                foreach ($data['spaceAttribute'] as $space) {
                    if ($space['date'] === $request->date) {
                        if ($totalPerson > $space['space']) {
                            return back()->with('error', 'You can book only ' . $space['space'] . ' person(s) for this date.');
                        }
                        break;
                    }
                }

                $adultTotalPrice = ($request->totalAdult ?? 0) * $data['package']->adult_price;
                $childrenTotalPrice = ($request->totalChildren ?? 0) * $data['package']->children_Price;
                $infantTotalPrice = ($request->totalInfant ?? 0) * $data['package']->infant_price;

                $totalPrice = $adultTotalPrice + $childrenTotalPrice + $infantTotalPrice;


                if ($data['package']->discount == 1) {
                    $type = $data['package']->discount_type;

                    if ($type == 0) {
                        $caldiscountAmount = (($totalPrice * $data['package']->discount_amount) / 100);
                        $totalPrice = $totalPrice - $caldiscountAmount;
                    } elseif ($type == 1) {
                        $caldiscountAmount = $data['package']->discount_amount;
                        $totalPrice = $totalPrice - $caldiscountAmount;
                    }
                }
            }


            if (!$instant) {
                $instant = new Booking();

                $instantTimeSlot = $data['package']->timeSlot[0] ?? null;
                $this->populateBookingFromRequest($instant, $request, $totalPrice, $totalPerson, $instantTimeSlot);

                $this->populateBookingFromPackage($instant, $data['package']);

                $this->populateBookingFromUser($instant, $user);

                $instant->discount_amount = 0;

                $instant->save();
            }

            return view(template() . 'frontend.checkout.userInfo', $data, compact('instant'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    private function populateBookingFromRequest($booking, $request, $totalPrice, $totalPerson, $instantTimeSlot)
    {
        $booking->date = $request->date;
        if (!is_null($request->time_slot)) {
            $booking->time_slot = $request->time_slot;
        } elseif (!is_null($instantTimeSlot)) {
            $booking->time_slot = $instantTimeSlot;
        } else {
            $booking->time_slot = null;
        }
        $booking->total_price = $totalPrice;
        $booking->total_adult = $request->totalAdult;
        $booking->total_children = $request->totalChildren;
        $booking->total_infant = $request->totalInfant;
        $booking->total_person = $totalPerson;
    }

    private function populateBookingFromPackage($booking, $package)
    {
        $booking->package_id = $package->id;
        $booking->package_title = $package->title;
        $booking->duration = $package->duration;
        $booking->start_price = $package->adult_price;
    }

    private function populateBookingFromUser($booking, $user)
    {
        $booking->fname = $user->firstname;
        $booking->lname = $user->lastname;
        $booking->email = $user->email;
        $booking->phone = $user->phone_code . $user->phone;
        $booking->postal_code = $user->zip_code;
        $booking->city = $user->city;
        $booking->state = $user->state;
        $booking->country = $user->country;
        $booking->address_one = $user->addressOne;
        $booking->address_two = $user->addressTwo;
        $booking->user_id = $user->id;
    }

    public function checkoutTravelersDetails(Request $request)
    {

        $request->validate([
            'fname' => 'required',
            'lname' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'address_one' => 'required',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
        ]);

        $package = Package::with('category:id,name')->where('slug', $request->package)->first();
        if (!$package) {
            return response()->json([
                'success' => false,
                'message' => 'Package Not Found.',
            ]);
        }
        $instant = Booking::where('id', $request->booking)->where('status', 0)->first();
        if (!$instant) {
            return response()->json([
                'success' => false,
                'message' => 'Booking Not Found.',
            ]);
        }
        $instant->fname = $request->fname;
        $instant->lname = $request->lname;
        $instant->email = $request->email;
        $instant->phone = $request->phone;
        $instant->address_one = $request->address_one;
        $instant->address_two = $request->address_two;
        $instant->city = $request->city;
        $instant->state = $request->state;
        $instant->country = $request->country;
        $instant->postal_code = $request->postalCode;
        $instant->message = $request->message;
        $instant->date = $request->date;
        $instant->save();

        return response()->json([
            'success' => true,
            'package' => $package,
            'instant' => $instant,
        ]);
    }

    public function getTravel($uid)
    {
        try {
            $instant = Booking::where('uid', $uid)->firstOr(function () {
                throw new \Exception('The booking record was not found.');
            });

            $package = Package::with('category:id,name')->where('id', $instant->package_id)->firstOr(function () {
                throw new \Exception('The package was not found.');
            });
            $seoData = Page::where('name', 'packages')->select(['id', 'page_title', 'meta_title', 'meta_keywords', 'meta_description', 'og_description', 'meta_robots', 'meta_image_driver', 'meta_image', 'breadcrumb_status', 'breadcrumb_image', 'breadcrumb_image_driver'])->first();

            $pageSeo = [
                'id' => $seoData->id,
                'page_title' => 'Package Details',
                'meta_title' => $seoData->meta_title,
                'meta_keywords' => implode(',', $seoData->meta_keywords ?? []),
                'meta_description' => $seoData->meta_description,
                'og_description' => $seoData->og_description,
                'meta_robots' => $seoData->meta_robots,
                'meta_image' => $seoData ? getFile($seoData->meta_image_driver, $seoData->meta_image) : null,
                'breadcrumb_status' => $seoData->breadcrumb_status ?? null,
                'breadcrumb_image' => $seoData->breadcrumb_status
                    ? getFile($seoData->breadcrumb_image_driver, $seoData->breadcrumb_image)
                    : null,
            ];

            return view(template() . 'frontend.checkout.travelerInfo', compact('package', 'pageSeo', 'instant'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

    }

    public function checkoutTravelersPayment(Request $request)
    {
        $messages = [
            'fname_adult.*.required' => 'Please enter the first name for adult.',
            'fname_adult.*.string' => 'The first name for adults must be a string.',
            'fname_adult.*.max' => 'The first name for adults cannot exceed 255 characters.',
            'lname_adult.*.required' => 'Please enter the last name for adult.',
            'lname_adult.*.string' => 'The last name for adults must be a string.',
            'lname_adult.*.max' => 'The last name for adults cannot exceed 255 characters.',
            'date_adult.*.required' => 'Please enter the birth date for adult.',
            'date_adult.*.date' => 'The birth date for adults must be a valid date.',
            'fname_child.*.required' => 'Please enter the first name for child.',
            'fname_child.*.string' => 'The first name for children must be a string.',
            'fname_child.*.max' => 'The first name for children cannot exceed 255 characters.',
            'lname_child.*.required' => 'Please enter the last name for child.',
            'lname_child.*.string' => 'The last name for children must be a string.',
            'lname_child.*.max' => 'The last name for children cannot exceed 255 characters.',
            'date_child.*.required' => 'Please enter the birth date for child.',
            'date_child.*.date' => 'The birth date for children must be a valid date.',
            'fname_infant.*.required' => 'Please enter the first name for infant.',
            'fname_infant.*.string' => 'The first name for infants must be a string.',
            'fname_infant.*.max' => 'The first name for infants cannot exceed 255 characters.',
            'lname_infant.*.required' => 'Please enter the last name for infant.',
            'lname_infant.*.string' => 'The last name for infants must be a string.',
            'lname_infant.*.max' => 'The last name for infants cannot exceed 255 characters.',
            'date_infant.*.required' => 'Please enter the birth date for infant.',
            'date_infant.*.date' => 'The birth date for infants must be a valid date.',
        ];

        $request->validate([
            'fname_adult.*' => 'required|string|max:255',
            'lname_adult.*' => 'required|string|max:255',
            'date_adult.*' => 'required|date',
            'fname_child.*' => 'required|string|max:255',
            'lname_child.*' => 'required|string|max:255',
            'date_child.*' => 'required|date',
            'fname_infant.*' => 'required|string|max:255',
            'lname_infant.*' => 'required|string|max:255',
            'date_infant.*' => 'required|date',
        ], $messages);

        $instant = Booking::where('id', $request->booking)->first();
        if (!$instant) {
            return response()->json([
                'success' => false,
                'message' => 'Booking Not Found.',
            ]);
        }
        if ($request->filled(['fname_adult', 'lname_adult', 'date_adult'])) {
            $instant->adult_info = $this->formatTravelerInfo($request->fname_adult, $request->lname_adult, $request->date_adult);
        }

        if ($request->filled(['fname_child', 'lname_child', 'date_child'])) {
            $instant->child_info = $this->formatTravelerInfo($request->fname_child, $request->lname_child, $request->date_child);
        }

        if ($request->filled(['fname_infant', 'lname_infant', 'date_infant'])) {
            $instant->infant_info = $this->formatTravelerInfo($request->fname_infant, $request->lname_infant, $request->date_infant);
        }

        $instant->save();

        return response()->json([
            'success' => true,
            'instant' => $instant,
        ]);

    }

    public function checkoutPaymentForm($uid)
    {
        try {
            $instant = Booking::where('uid', $uid)->firstOr(function () {
                throw new \Exception('The booking record was not found.');
            });
            $data['package'] = Package::with('category:id,name')->where('id', $instant->package_id)->firstOr(function () {
                throw new \Exception('The package was not found.');
            });

            $seoData = Page::where('name', 'packages')->select(['page_title', 'meta_title', 'meta_keywords', 'meta_description', 'og_description', 'meta_robots', 'meta_image_driver', 'meta_image', 'breadcrumb_status', 'breadcrumb_image', 'breadcrumb_image_driver'])->first();

            $data['pageSeo'] = [
                'page_title' => 'Package Details',
                'meta_title' => $seoData->meta_title,
                'meta_keywords' => implode(',', $seoData->meta_keywords ?? []),
                'meta_description' => $seoData->meta_description,
                'og_description' => $seoData->og_description,
                'meta_robots' => $seoData->meta_robots,
                'meta_image' => $seoData
                    ? getFile($seoData->meta_image_driver, $seoData->meta_image)
                    : null,
                'breadcrumb_status' => $seoData->breadcrumb_status ?? null,
                'breadcrumb_image' => $seoData->breadcrumb_status
                    ? getFile($seoData->breadcrumb_image_driver, $seoData->breadcrumb_image)
                    : null,
            ];

            $selectedModel = getGatewayModel($data['package']);
            if ($selectedModel == UserGateway::class) {
                $data['gateway'] = UserGateway::where('status', 1)->where('user_id', $data['package']->owner_id)->orderBy('sort_by', 'asc')->get();
            } else {
                $data['gateway'] = Gateway::where('status', 1)->orderBy('sort_by', 'asc')->get();
            }

            return view(template() . 'frontend.checkout.checkout_form', array_merge($data, ['instant' => $instant]));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }


    private function formatTravelerInfo($firstNames, $lastNames, $birthDates)
    {
        return array_map(function ($firstName, $lastName, $birthDate) {
            return [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'full_name' => $firstName . ' ' . $lastName,
                'birth_date' => $birthDate
            ];
        }, $firstNames, $lastNames, $birthDates);
    }


    public function makePayment(Request $request)
    {
        try {
            $amount = $request->amount;
            $gateway = $request->gateway_id;
            $currency = $request->supported_currency ?? $request->base_currency;
            $cryptoCurrency = $request->supported_crypto_currency;
            $booking = Booking::with(['package'])->where('id', $request->booking)->firstOr(function () {
                throw new \Exception('The booking record was not found.');
            });

            $selectedModel = getGatewayModel($booking->package);
            $checkAmount = $this->checkAmountValidate($amount, $currency, $gateway, $cryptoCurrency, 'yes', $selectedModel);

            if ($checkAmount['status'] == false) {
                return back()->with('error', $checkAmount['msg']);
            }

            $deposit = Deposit::create([
                'user_id' => Auth::user()->id,
                'depositable_type' => Booking::class,
                'depositable_id' => $booking->id,
                'gatewayable_id' => $gateway,
                'gatewayable_type' => $selectedModel,
                'vendor_id' => $booking->package->owner_id ?? null,
                'payment_method_id' => $checkAmount['gateway_id'],
                'payment_method_currency' => $checkAmount['currency'],
                'amount' => $checkAmount['amount'],
                'percentage_charge' => $checkAmount['percentage_charge'],
                'fixed_charge' => $checkAmount['fixed_charge'],
                'payable_amount' => $checkAmount['payable_amount'],
                'base_currency_charge' => $checkAmount['charge_baseCurrency'],
                'payable_amount_in_base_currency' => $checkAmount['payable_amount_baseCurrency'],
                'status' => 0,
            ]);

            return redirect(route('payment.process', $deposit->trx_id));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

    }

    public function checkCoupon(Request $request)
    {
        $request->validate([
            'coupon' => 'required|string',
        ]);

        $couponCode = $request->input('coupon');
        $amount = $request->input('amount');
        $instantSave = $request->input('instantId');
        $coupon = Coupon::whereRaw('BINARY coupon_code = ?', [$couponCode])->first();

        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => 'Coupon not found.',
            ], 404);
        }

        $instant = Booking::find($instantSave);

        $currentDate = now();
        $endDate = Carbon::createFromFormat('Y-m-d', $coupon->end_date);

        if ($instant->coupon != 1) {
            if ($currentDate->gt($endDate)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Coupon validity expired.',
                ], 404);
            }

            $package = Package::where('id', $instant->package_id)->where('status', 1)->first();

            if (!$package) {
                return response()->json([
                    'success' => false,
                    'message' => 'Package not found.',
                ], 404);
            }

            if ($package->discount == 0) {
                $discount = ($coupon->discount_type == 0) ? $coupon->discount : ($amount * $coupon->discount) / 100;

                $instant->coupon = 1;
                $instant->cupon_number = $coupon->coupon_code;
                $instant->cupon_status = $coupon->discount_type;
                $instant->discount_amount = $discount;
                $instant->total_price = $instant->total_price - $discount;
                $instant->save();

                return response()->json([
                    'success' => true,
                    'data' => $instant,
                ], 200);
            }

            return response()->json([
                'success' => false,
                'message' => 'Coupon not allowed for this package.',
            ], 404);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Coupon not allowed for this package.',
            ], 404);
        }

    }

    public function dateUpdate(Request $request)
    {
        $id = $request->input('id');
        $date = $request->input('date');
        $instant = Booking::where('id', $id)->first();
        if (!$instant) {
            return response()->json(['error' => 'Booking with id ' . $id . ' not found'], 404);
        }
        $formatted_date = date('Y-m-d', strtotime($date));

        $instant->date = $formatted_date;
        $instant->save();

        return response()->json(['message' => 'Date updated successfully']);
    }


}
