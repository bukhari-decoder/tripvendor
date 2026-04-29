<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\Gateway;
use App\Models\Plan;
use App\Models\PlanPurchase;
use App\Models\Transaction;
use App\Models\VendorInfo;
use App\Traits\PaymentValidationCheck;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Mockery\Exception;
use function Laravel\Prompts\text;

class PlanController extends Controller
{
    use PaymentValidationCheck;

    public function planSelect(Request $request)
    {
        try {
            if (!Auth::check()) {
                return redirect()->route('login')->with('error', 'You need to be logged in to perform this action.');
            }

            if (auth()->user()->role != 1){
                return back()->with('error', 'Oops! This feature is only available for vendors. Please switch to a vendor account to purchase a plan.');
            }

            $newPlan = Plan::where('status', 1)
                ->where('id', $request->selectedPlan)
                ->firstOr(function () {
                    throw new \Exception('Plan not found.');
                });

            $oldPurchase = PlanPurchase::where('user_id', auth()->user()->id)
                ->where('plan_id', $newPlan->id)
                ->where('status', 0)
                ->where('expiry_date', '>', now())
                ->first();

            if (!is_null($oldPurchase)) {
                if ($oldPurchase->price > $newPlan->price) {
                    return back()->with('error', 'Oops! You’re trying to choose a plan that costs less than your previous one. Please select a plan of equal or higher value.');
                }
            }

            if ($newPlan->validity_type == 'daily') {
                $expiryDate = Carbon::now()->addDays($newPlan->validity)->toDateString();
            } elseif ($newPlan->validity_type == 'weekly') {
                $expiryDate = Carbon::now()->addWeeks($newPlan->validity)->toDateString();
            } elseif ($newPlan->validity_type == 'monthly') {
                $expiryDate = Carbon::now()->addMonths($newPlan->validity)->toDateString();
            } elseif ($newPlan->validity_type == 'yearly') {
                $expiryDate = Carbon::now()->addYears($newPlan->validity)->toDateString();
            }

            $purchase = new PlanPurchase();
            $purchase->user_id = auth()->user()->id;
            $purchase->plan_id = $newPlan->id;
            $purchase->price = $newPlan->price;
            $purchase->validity_type = $newPlan->validity_type;
            $purchase->validity = $newPlan->validity;
            $purchase->expiry_date = $expiryDate;
            $purchase->listing_allowed = $newPlan->listing_allowed;
            $purchase->featured_listing = $newPlan->featured_listing;
            $purchase->ai_feature = $newPlan->ai_feature;
            $purchase->save();

            $newPayment = [
                'make_payment' => true,
                'purchaseID' => $purchase->id,

            ];
            session()->put('make_payment', json_encode($newPayment));

            return redirect()->route('user.make.payment.details');
        }catch (Exception $e){
            return back()->with('error', $e->getMessage());
        }
    }

    public function makePaymentDetails()
    {

        try {
            $user = Auth::user();
            $makePaymentData = session()->get('make_payment');
            if ($makePaymentData == null) {
                return redirect()->route('page','plans');
            }
            $makePaymentData = json_decode($makePaymentData);
            if ($makePaymentData->make_payment == false) {
                return redirect()->route('page','plans');
            }

            $data['purchase'] = PlanPurchase::where('id', $makePaymentData->purchaseID)->where('status', 0)->firstOr(function () {
                throw new \Exception('This purchase record is not available now');
            });

            $data['gateways'] = Gateway::where('status', 1)->orderBy('sort_by', 'asc')->get();
            return view(template() . 'frontend.checkout.plan_payment', $data, compact('user'));

        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function makePayment(Request $request)
    {
        try {
            if (!$request->gateway_id){
                return back()->with('error', 'Gateway is Missing');
            }

            $purchase = PlanPurchase::select(['id', 'plan_id','price'])->where('id', $request->purchase_id)->firstOr(function () {
                throw new \Exception('Purchase Record Not Found.');
            });

            $plan = Plan::where('id', $purchase->plan_id)->firstOr(function () {
                throw new \Exception('Plan Not Found.');
            });


            $oldPurchase = PlanPurchase::select(['id', 'user_id', 'plan_id', 'price', 'expiry_date', 'validity', 'validity_type'])
                ->where('user_id', auth()->user()->id)
                ->where('plan_id', $plan->id)
                ->where('status', 1)
                ->where('expiry_date', '>', now())
                ->first();

            if (!is_null($oldPurchase)) {
                if ($oldPurchase->price > $purchase->price) {
                    return back()->with('error', 'Oops! You’re trying to choose a plan that costs less than your previous one. Please select a plan of equal or higher value.');
                }
            }

            $amount = $purchase->price;
            $gateway = $request->gateway_id;
            $currency = $request->supported_currency ?? $request->base_currency;
            $cryptoCurrency = $request->supported_crypto_currency;
            $checkAmount = $this->checkAmountValidate($amount, $currency, $gateway, $cryptoCurrency, $amountType = 'yes', Gateway::class);

            if ($checkAmount['status'] == false) {
                return back()->with('error', $checkAmount['message']);
            }

            $purchase->gateway_id = $gateway;
            $purchase->save();

            $deposit = Deposit::create([
                'user_id' => Auth::user()->id,
                'depositable_type' => PlanPurchase::class,
                'depositable_id' => $purchase->id,
                'gatewayable_id' => $purchase->gateway_id,
                'gatewayable_type' => Gateway::class,
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


    public function autoRenew(Request $request)
    {
        if ($request->confirm == 1){
            $user = auth()->user();
            $user->vendorInfo->auto_renew_current_plan = ($user->vendorInfo->auto_renew_current_plan == 1) ? 0 : 1;
            $user->vendorInfo->save();

            $message = 'Auto-renewal has been successfully '. (($user->vendorInfo->auto_renew_current_plan == 1) ? 'Enabled' : 'Disabled') .' for your plan.';

            return back()->with('success', $message);
        }
    }


    public function makeEcocashPaymentDetails(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            // ── 1. Validate incoming AJAX data ───────────────────────────────────
            $request->validate([
                'phone'       => ['required', 'regex:/^2637[78]\d{7}$/'],
                'currency'    => ['required', 'in:USD,ZWL'],
                'amount'      => ['required', 'numeric', 'min:0.01'],
                'purchase_id' => ['required', 'integer'],
                'gateway_id'  => ['required', 'integer'],
            ]);

            // ── 2. Load & verify the purchase record ─────────────────────────────
            $purchase = PlanPurchase::select(['id', 'plan_id', 'price'])
                ->where('id', $request->purchase_id)
                ->firstOr(function () {
                    throw new \Exception('Purchase Record Not Found.');
                });

            $plan = Plan::where('id', $purchase->plan_id)
                ->firstOr(function () {
                    throw new \Exception('Plan Not Found.');
                });

            // ── 3. Prevent downgrade ─────────────────────────────────────────────
            $oldPurchase = PlanPurchase::select(['id', 'user_id', 'plan_id', 'price', 'expiry_date'])
                ->where('user_id', auth()->id())
                ->where('plan_id', $plan->id)
                ->where('status', 1)
                ->where('expiry_date', '>', now())
                ->first();

            if (!is_null($oldPurchase) && $oldPurchase->price > $purchase->price) {
                return response()->json([
                    'status'  => 'failed',
                    'message' => "You're trying to choose a plan that costs less than your previous one. Please select a plan of equal or higher value.",
                ]);
            }

            // ── 4. Fire EcoCash USSD push & wait for approval ────────────────────
            //    checkAmountValidate() is intentionally skipped — EcoCash is a
            //    custom gateway outside the standard gateway currency system.
            //    Amount is taken directly from the purchase record (not user input).
            $ecocashResult = $this->startecocash(
                $request->phone,
                $request->currency,
                $purchase->price
            );
//            dd($ecocashResult);
//  $ecocashResult = 'SUCCESSFUL';

            // ── 5. Handle EcoCash result ─────────────────────────────────────────
            if ($ecocashResult !== 'SUCCESSFUL') {
                return response()->json([
                    'status'  => 'failed',
                    'message' => 'EcoCash payment was not completed. Please try again.',
                ]);
            }

            // ── 6. Attach gateway to purchase ────────────────────────────────────
            $purchase->gateway_id = $request->gateway_id;
            $purchase->save();

            // ── 7. Create deposit record ─────────────────────────────────────────
            //    EcoCash has no system charges, so charge fields are 0.
            //    Status is set to 1 (paid) immediately — EcoCash already confirmed.
            $deposit = Deposit::create([
                'user_id'                         => auth()->id(),
                'depositable_type'                => PlanPurchase::class,
                'depositable_id'                  => $purchase->id,
                'gatewayable_id'                  => $request->gateway_id,
                'gatewayable_type'                => Gateway::class,
                'payment_method_id'               => $request->gateway_id,
                'payment_method_currency'         => $request->currency,   // USD or ZWL
                'amount'                          => $purchase->price,
                'percentage_charge'               => 0,
                'fixed_charge'                    => 0,
                'payable_amount'                  => $purchase->price,
                'base_currency_charge'            => 0,
                'payable_amount_in_base_currency' => $purchase->price,
                'status'                          => 1,  // paid
            ]);

            // ── 8. Activate the plan purchase ────────────────────────────────────
            $purchase->update(['status' => 1]);

            \Log::info('EcoCash payment successful', [
                'user_id'     => auth()->id(),
                'purchase_id' => $purchase->id,
                'deposit_trx' => $deposit->trx_id,
                'amount'      => $purchase->price,
                'currency'    => $request->currency,
            ]);
            $vendorId = auth()->id();

            $vendorInfo = \App\Models\VendorInfo::firstOrNew([
                'vendor_id' => $vendorId
            ]);


            $validity = $plan->validity ?? 0;
            $type = $plan->validity_type ?? 'daily';

            $expiryDate = now();

            switch ($type) {
                case 'daily':
                    $expiryDate = now()->addDays($validity);
                    break;
                case 'weekly':
                    $expiryDate = now()->addWeeks($validity);
                    break;
                case 'monthly':
                    $expiryDate = now()->addMonths($validity);
                    break;
                case 'yearly':
                    $expiryDate = now()->addYears($validity);
                    break;
            }

            $vendorInfo->active_plan = $purchase->plan_id;
            $vendorInfo->current_plan_purchase_date = now();
            $vendorInfo->current_plan_expiry_date = $expiryDate;

            $vendorInfo->posted_listing = $vendorInfo->posted_listing ?? 0;
            $vendorInfo->current_plan_posted_listing = $plan->total_listing ?? 0;

            $vendorInfo->badge_id = null;
            $vendorInfo->auto_renew_current_plan = 0;
            $vendorInfo->avg_rating = $vendorInfo->avg_rating ?? 0;

            $vendorInfo->save();

            return response()->json([
                'status'   => 'success',
                'message'  => 'EcoCash payment completed successfully!',
                'redirect' => route('user.dashboard'), // ← update to your success/dashboard route
            ]);

        } catch (\Exception $e) {
            \Log::error('EcoCash payment error: ' . $e->getMessage());

            return response()->json([
                'status'  => 'failed',
                'message' => $e->getMessage(),
            ]);
        }
    }

// ─────────────────────────────────────────────────────────────────────────────
//  EcoCash private helpers
// ─────────────────────────────────────────────────────────────────────────────

    private function startecocash($ecocashnumber, $currency, $amount): string
    {
        $responseec = 'ERROR';

        if ($currency == 'USD') {
            $currency = 'USD-FCA';
        }


        $corelator = mt_rand(1000, 9999);
        if (str_starts_with($ecocashnumber, '263')) {
            $message = substr($ecocashnumber, 3);
        } elseif (str_starts_with($ecocashnumber, '07')) {
            $message = substr($ecocashnumber, 1);
        } else {
            return 'FAILED';
        }
//        $ecocashnumber = "0772629848";
//        $message = "0772629848";
//        dd($amount,$currency,$ecocashnumber);

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL            => 'https://payonline.econet.co.zw/ecocashGateway/payment/v1/transactions/amount',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => 'POST',
            CURLOPT_POSTFIELDS     => '
        {
            "clientCorrelator":' . $corelator . ',
            "notifyUrl":"http://chat.co.zw/payments/received",
            "referenceCode":' . $corelator . ',
            "tranType":"MER",
            "endUserId":' . $message . ',
            "remarks":"OyOs",
            "transactionOperationStatus":"Charged",
            "paymentAmount":
            {
                "charginginformation":
                {
                    "amount":' . $amount . ',
                    "currency":"' . $currency . '",
                    "description":"OyOs Online Payment"
                },
                "chargeMetaData":
                {
                    "channel":"WEB",
                    "purchaseCategoryCode":"Online Payment",
                    "onBeHalfOf":"OyOs"
                }
            },
            "merchantCode":"030528",
            "merchantPin":"3020",
            "merchantNumber":"779806308",
            "currencyCode":"' . $currency . '",
            "countryCode":"ZW",
            "terminalID":"TERM123456",
            "location":"Angwa Street",
            "superMerchantName":"OyOs",
            "merchantName":"OyOs"
        }',
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Basic R29FdmVudHM6JEczUzNyNGNVQFol',
            ],
        ]);

        curl_exec($curl);
        curl_close($curl);
//        dd($curl);

        sleep(13);
        $statusmessage = $this->checkecocash($corelator);
//        dd($statusmessage);
        if ($statusmessage == 'COMPLETED') {
            $responseec = 'SUCCESSFUL';
        } elseif ($statusmessage == 'PENDING SUBSCRIBER VALIDATION') {
            $statusmessage = $this->checkecocash($corelator);
            if ($statusmessage == 'COMPLETED') {
                $responseec = 'SUCCESSFUL';
            } else {
                $responseec = 'FAILED';
            }
        } else {
            $responseec = 'FAILED';
        }

        return $responseec;
    }

    private function checkecocash($corelator): string
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://payonline.econet.co.zw/ecocashGateway/payment/v1/{$corelator}/transactions/amount/{$corelator}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTPHEADER => [
                'Authorization: Basic R29FdmVudHM6JEczUzNyNGNVQFol',
            ],
        ]);

        $response = curl_exec($curl);

        if ($response === false) {
            \Log::error('EcoCash Status Error: ' . curl_error($curl));
            curl_close($curl);
            return 'FAILED';
        }

        curl_close($curl);

        \Log::info('EcoCash Status Response', [
            'response' => $response
        ]);
        $data = json_decode($response, true);


        return $data['transactionOperationStatus'] ?? 'FAILED';
    }

//new
//    public function makeEcocashPaymentDetails(Request $request): \Illuminate\Http\JsonResponse
//    {
//        try {
//            // ── 1. Validate ──────────────────────────────────────────────────────
//            $request->validate([
//                'phone'       => ['required', 'regex:/^2637[78]\d{7}$/'],
//                'currency'    => ['required', 'in:USD,ZWL'],
//                'amount'      => ['required', 'numeric', 'min:0.01'],
//                'purchase_id' => ['required', 'integer'],
//                'gateway_id'  => ['required', 'integer'],
//            ]);
//
//            // ── 2. Load purchase & plan ──────────────────────────────────────────
//            $purchase = PlanPurchase::select(['id', 'plan_id', 'price'])
//                ->where('id', $request->purchase_id)
//                ->firstOr(function () {
//                    throw new \Exception('Purchase Record Not Found.');
//                });
//
//            $plan = Plan::where('id', $purchase->plan_id)
//                ->firstOr(function () {
//                    throw new \Exception('Plan Not Found.');
//                });
//
//            // ── 3. Prevent downgrade ─────────────────────────────────────────────
//            $oldPurchase = PlanPurchase::select(['id', 'user_id', 'plan_id', 'price', 'expiry_date'])
//                ->where('user_id', auth()->id())
//                ->where('plan_id', $plan->id)
//                ->where('status', 1)
//                ->where('expiry_date', '>', now())
//                ->first();
//
//            if (!is_null($oldPurchase) && $oldPurchase->price > $purchase->price) {
//                return response()->json([
//                    'status'  => 'failed',
//                    'message' => "You're trying to choose a plan that costs less than your previous one.",
//                ]);
//            }
//
//            // ── 4. Fire EcoCash & wait ───────────────────────────────────────────
//            $ecocashResult = $this->startecocash(
//                $request->phone,
//                $request->currency,
//                $purchase->price
//            );
//            dd($ecocashResult);
//
//            if ($ecocashResult !== 'SUCCESSFUL') {
//                return response()->json([
//                    'status'  => 'failed',
//                    'message' => 'EcoCash payment was not completed. Please try again.',
//                ]);
//            }
//
//            // ── 5. Save purchase & deposit ───────────────────────────────────────
//            $purchase->gateway_id = $request->gateway_id;
//            $purchase->save();
//
//            $deposit = Deposit::create([
//                'user_id'                         => auth()->id(),
//                'depositable_type'                => PlanPurchase::class,
//                'depositable_id'                  => $purchase->id,
//                'gatewayable_id'                  => $request->gateway_id,
//                'gatewayable_type'                => Gateway::class,
//                'payment_method_id'               => $request->gateway_id,
//                'payment_method_currency'         => $request->currency,
//                'amount'                          => $purchase->price,
//                'percentage_charge'               => 0,
//                'fixed_charge'                    => 0,
//                'payable_amount'                  => $purchase->price,
//                'base_currency_charge'            => 0,
//                'payable_amount_in_base_currency' => $purchase->price,
//                'status'                          => 1,
//            ]);
//
//            $purchase->update(['status' => 1]);
//            $vendorId = auth()->id();
//
//            $vendorInfo = \App\Models\VendorInfo::firstOrNew([
//                'vendor_id' => $vendorId
//            ]);
//
//
//            $validity = $plan->validity ?? 0;
//            $type = $plan->validity_type ?? 'daily';
//
//            $expiryDate = now();
//
//            switch ($type) {
//                case 'daily':
//                    $expiryDate = now()->addDays($validity);
//                    break;
//                case 'weekly':
//                    $expiryDate = now()->addWeeks($validity);
//                    break;
//                case 'monthly':
//                    $expiryDate = now()->addMonths($validity);
//                    break;
//                case 'yearly':
//                    $expiryDate = now()->addYears($validity);
//                    break;
//            }
//
//            $vendorInfo->active_plan = $purchase->plan_id;
//            $vendorInfo->current_plan_purchase_date = now();
//            $vendorInfo->current_plan_expiry_date = $expiryDate;
//
//            $vendorInfo->posted_listing = $vendorInfo->posted_listing ?? 0;
//            $vendorInfo->current_plan_posted_listing = $plan->total_listing ?? 0;
//
//            $vendorInfo->badge_id = null;
//            $vendorInfo->auto_renew_current_plan = 0;
//            $vendorInfo->avg_rating = $vendorInfo->avg_rating ?? 0;
//
//            \Log::info('EcoCash payment successful', [
//                'user_id'     => auth()->id(),
//                'purchase_id' => $purchase->id,
//                'deposit_trx' => $deposit->trx_id,
//            ]);
//
//            return response()->json([
//                'status'   => 'success',
//                'message'  => 'EcoCash payment completed successfully!',
//                'redirect' => route('user.plan.index'),
//            ]);
//
//        } catch (\Exception $e) {
//            \Log::error('EcoCash payment error: ' . $e->getMessage());
//            return response()->json(['status' => 'failed', 'message' => $e->getMessage()]);
//        }
//    }
//    private function startecocash(string $ecocashnumber, string $currency, $amount): string
//    {
//        if ($currency === 'USD') {
//            $currency = 'USD-FCA';
//        }
//
//        $corelator = mt_rand(1000, 9999);
//
//        // Strip country code → 263771234567 becomes 771234567
//        $endUserId = substr($ecocashnumber, 3);
//
//        // Build the raw JSON string exactly as the original working script does.
//        // Do NOT use json_encode() or pass an array — EcoCash WAF blocks those.
//        $rawBody = '{
//        "clientCorrelator":' . $corelator . ',
//        "notifyUrl":"http://chat.co.zw/payments/received",
//        "referenceCode":' . $corelator . ',
//        "tranType":"MER",
//        "endUserId":' . $endUserId . ',
//        "remarks":"OyOs",
//        "transactionOperationStatus":"Charged",
//        "paymentAmount":
//        {
//            "charginginformation":
//            {
//                "amount":' . $amount . ',
//                "currency":"' . $currency . '",
//                "description":"OyOs Online Payment"
//            },
//            "chargeMetaData":
//            {
//                "channel":"WEB",
//                "purchaseCategoryCode":"Online Payment",
//                "onBeHalfOf":"OyOs"
//            }
//        },
//        "merchantCode":"030528",
//        "merchantPin":"3020",
//        "merchantNumber":"779806308",
//        "currencyCode":"' . $currency . '",
//        "countryCode":"ZW",
//        "terminalID":"TERM123456",
//        "location":"Angwa Street",
//        "superMerchantName":"OyOs",
//        "merchantName":"OyOs"
//    }';
//
//        $curl = curl_init();
//        curl_setopt_array($curl, [
//            CURLOPT_URL            => 'https://payonline.econet.co.zw/ecocashGateway/payment/v1/transactions/amount',
//            CURLOPT_RETURNTRANSFER => true,
//            CURLOPT_ENCODING       => '',
//            CURLOPT_MAXREDIRS      => 10,
//            CURLOPT_TIMEOUT        => 0,          // no timeout — matches original script
//            CURLOPT_FOLLOWLOCATION => true,
//            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
//            CURLOPT_CUSTOMREQUEST  => 'POST',
//            CURLOPT_POSTFIELDS     => $rawBody,   // raw string, NOT an array
//            CURLOPT_HTTPHEADER     => [
//                'Content-Type: application/json',
//                'Authorization: Basic R29FdmVudHM6JEczUzNyNGNVQFol',
//            ],
//        ]);
//
//        $initResponse = curl_exec($curl);
//
//        if (curl_errno($curl)) {
//            \Log::error('EcoCash initiation cURL error: ' . curl_error($curl));
//            curl_close($curl);
//            return 'FAILED';
//        }
//
//        curl_close($curl);
//        dd($initResponse);
//        \Log::info('EcoCash initiation response', [
//            'corelator' => $corelator,
//            'response'  => $initResponse,
//        ]);
//
//        // First status check immediately after POST
//        $statusmessage = $this->checkecocash($corelator);
//
//        if ($statusmessage === 'COMPLETED') {
//            return 'SUCCESSFUL';
//        }
//
//        if ($statusmessage === 'PENDING SUBSCRIBER VALIDATION') {
//            sleep(10);
//            $statusmessage = $this->checkecocash($corelator);
//
//            if ($statusmessage === 'COMPLETED') {
//                return 'SUCCESSFUL';
//            }
//        }
//
//        \Log::warning('EcoCash not completed', [
//            'corelator'    => $corelator,
//            'final_status' => $statusmessage,
//        ]);
//
//        return 'FAILED';
//    }
//
//    private function checkecocash(int $corelator): string
//    {
//        $curl = curl_init();
//        curl_setopt_array($curl, [
//            CURLOPT_URL            => "https://payonline.econet.co.zw/ecocashGateway/payment/v1/{$corelator}/transactions/amount/{$corelator}",
//            CURLOPT_RETURNTRANSFER => true,
//            CURLOPT_ENCODING       => '',
//            CURLOPT_MAXREDIRS      => 10,
//            CURLOPT_TIMEOUT        => 0,
//            CURLOPT_FOLLOWLOCATION => true,
//            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
//            CURLOPT_CUSTOMREQUEST  => 'GET',
//            CURLOPT_HTTPHEADER     => [
//                'Authorization: Basic R29FdmVudHM6JEczUzNyNGNVQFol',
//            ],
//        ]);
//
//        $response = curl_exec($curl);
//
//        if ($response === false) {
//            \Log::error('EcoCash status cURL error: ' . curl_error($curl));
//            curl_close($curl);
//            return 'FAILED';
//        }
//
//        curl_close($curl);
//
//        \Log::info('EcoCash status response', [
//            'corelator' => $corelator,
//            'response'  => $response,
//        ]);
//
//        $data = json_decode($response, true);
//        return $data['transactionOperationStatus'] ?? 'FAILED';
//    }
//new2
//    public function makeEcocashPaymentDetails(Request $request): \Illuminate\Http\JsonResponse
//    {
//        try {
//            // ── 1. Validate ──────────────────────────────────────────────────────
//            $request->validate([
//                'phone'       => ['required', 'regex:/^2637[78]\d{7}$/'],
//                'currency'    => ['required', 'in:USD,ZWL'],
//                'amount'      => ['required', 'numeric', 'min:0.01'],
//                'purchase_id' => ['required', 'integer'],
//                'gateway_id'  => ['required', 'integer'],
//            ]);
//
//            // ── 2. Load purchase & plan ──────────────────────────────────────────
//            $purchase = PlanPurchase::select(['id', 'plan_id', 'price'])
//                ->where('id', $request->purchase_id)
//                ->firstOr(function () {
//                    throw new \Exception('Purchase Record Not Found.');
//                });
//
//            $plan = Plan::where('id', $purchase->plan_id)
//                ->firstOr(function () {
//                    throw new \Exception('Plan Not Found.');
//                });
//
//            // ── 3. Prevent downgrade ─────────────────────────────────────────────
//            $oldPurchase = PlanPurchase::select(['id', 'user_id', 'plan_id', 'price', 'expiry_date'])
//                ->where('user_id', auth()->id())
//                ->where('plan_id', $plan->id)
//                ->where('status', 1)
//                ->where('expiry_date', '>', now())
//                ->first();
//
//            if (!is_null($oldPurchase) && $oldPurchase->price > $purchase->price) {
//                return response()->json([
//                    'status'  => 'failed',
//                    'message' => "You're trying to choose a plan that costs less than your previous one.",
//                ]);
//            }
//
//            // ── 4. EcoCash payload — exactly like storeInvoiceForEcocash ─────────
//            $corelator = time() . rand(100,999);
//            $phone_trimmed = 263772629848;
//            $currency      = $request->currency === 'USD' ? 'USD-FCA' : $request->currency;
//
//            $payload = [
//                "clientCorrelator"           => $corelator,
//                "notifyUrl"                  => 'http://chat.co.zw/payments/received',
//                "referenceCode"              => $corelator,
//                "tranType"                   => "MER",
//                "endUserId" => (string) $phone_trimmed,
//                "remarks"                    => "OyOs",
//                "transactionOperationStatus" => "Charged",
//                "paymentAmount"              => [
//                    "charginginformation" => [
//                        "amount"      => $purchase->price,
//                        "currency"    => $currency,
//                        "description" => "OyOs Online Payment",
//                    ],
//                    "chargeMetaData" => [
//                        "channel"              => "WEB",
//                        "purchaseCategoryCode" => "Online Payment",
//                        "onBeHalfOf"           => "OyOs",
//                    ],
//                ],
//                "merchantCode"      => "030528",
//                "merchantPin"       => "3020",
//                "merchantNumber"    => "779806308",
//                "currencyCode"      => $currency,
//                "countryCode"       => "ZW",
//                "terminalID"        => "TERM123456",
//                "location"          => "Angwa Street",
//                "superMerchantName" => "OyOs",
//                "merchantName"      => "OyOs",
//            ];
//
//            // ── 5. Send request — exactly like storeInvoiceForEcocash ────────────
//            $response = Http::timeout(60)
//                ->withHeaders([
//                    'Content-Type'  => 'application/json',
//                    'Authorization' => 'Basic R29FdmVudHM6JEczUzNyNGNVQFol',
//                ])
//                ->post('https://payonline.econet.co.zw/ecocashGateway/payment/v1/transactions/amount', $payload);
//
//            if ($response->failed()) {
//                return response()->json([
//                    'status'  => 'failed',
//                    'message' => 'Payment request failed. Please try again.',
//                ]);
//            }
//            dd($response->body());
////            dd($response);
//
//            // ── 6. Status check — exactly like storeInvoiceForEcocash ────────────
//            sleep(8); // minimum wait
//
//            $status = $this->checkEcocashStatus($corelator);
//            dd($status);
//            if ($status === 'COMPLETED') {
//                return $this->handleEcocashSuccess($purchase, $request);
//            }
//
//            if ($status === 'PENDING SUBSCRIBER VALIDATION') {
//                sleep(10);
//                $status = $this->checkEcocashStatus($corelator);
//
//                if ($status === 'COMPLETED') {
//                    return $this->handleEcocashSuccess($purchase, $request);
//                }
//            }
//
//            return response()->json([
//                'status'  => 'failed',
//                'message' => 'Payment processing failed, please try again.',
//            ]);
//
//        } catch (\Exception $e) {
//            \Log::error('EcoCash payment error: ' . $e->getMessage());
//            return response()->json(['status' => 'failed', 'message' => $e->getMessage()]);
//        }
//    }
//
//// ── Exactly like storeInvoiceForEcocash::check_status() ──────────────────────
//    private function checkEcocashStatus(int $corelator): ?string
//    {
//        $response = Http::withHeaders([
//            'Authorization' => 'Basic R29FdmVudHM6JEczUzNyNGNVQFol',
//        ])->get("https://payonline.econet.co.zw/ecocashGateway/payment/v1/{$corelator}/transactions/amount/{$corelator}");
//
//        if ($response->failed()) {
//            return null;
//        }
//
//        $data = $response->json();
//        return $data['transactionOperationStatus'] ?? null;
//    }
//
//// ── Called when EcoCash returns COMPLETED ─────────────────────────────────────
//    private function handleEcocashSuccess(PlanPurchase $purchase, $request): \Illuminate\Http\JsonResponse
//    {
//        dd("DAS");
//        $purchase->gateway_id = $request->gateway_id;
//        $purchase->save();
//
//        $deposit = Deposit::create([
//            'user_id'                         => auth()->id(),
//            'depositable_type'                => PlanPurchase::class,
//            'depositable_id'                  => $purchase->id,
//            'gatewayable_id'                  => $request->gateway_id,
//            'gatewayable_type'                => Gateway::class,
//            'payment_method_id'               => $request->gateway_id,
//            'payment_method_currency'         => $request->currency,
//            'amount'                          => $purchase->price,
//            'percentage_charge'               => 0,
//            'fixed_charge'                    => 0,
//            'payable_amount'                  => $purchase->price,
//            'base_currency_charge'            => 0,
//            'payable_amount_in_base_currency' => $purchase->price,
//            'status'                          => 1,
//        ]);
//
//        $purchase->update(['status' => 1]);
//
//        \Log::info('EcoCash payment successful', [
//            'user_id'     => auth()->id(),
//            'purchase_id' => $purchase->id,
//            'deposit_trx' => $deposit->trx_id,
//        ]);
//
//        return response()->json([
//            'status'   => 'success',
//            'message'  => 'EcoCash payment completed successfully!',
//            'redirect' => route('user.plan.index'),
//        ]);
//    }
}
