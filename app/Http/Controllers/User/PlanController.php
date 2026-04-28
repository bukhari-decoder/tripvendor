<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\Gateway;
use App\Models\Plan;
use App\Models\PlanPurchase;
use App\Models\Transaction;
use App\Traits\PaymentValidationCheck;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
}
