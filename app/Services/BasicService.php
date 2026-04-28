<?php

namespace App\Services;


use App\Models\Booking;
use App\Models\Gateway;
use App\Models\PlanPurchase;
use App\Models\Transaction;
use App\Models\UserGateway;
use App\Traits\Notify;
use Carbon\Carbon;
use GPBMetadata\Google\Api\Auth;

class BasicService
{
    use Notify;

    public function setEnv($value)
    {
        $envPath = base_path('.env');
        $env = file($envPath);
        foreach ($env as $env_key => $env_value) {
            $entry = explode("=", $env_value, 2);
            $env[$env_key] = array_key_exists($entry[0], $value) ? $entry[0] . "=" . $value[$entry[0]] . "\n" : $env_value;
        }
        $fp = fopen($envPath, 'w');
        fwrite($fp, implode($env));
        fclose($fp);
    }

    public function preparePaymentUpgradation($deposit)
    {
        try {
            if ($deposit->status == 0 || $deposit->status == 2) {
                $deposit->status = 1;
                $deposit->save();

                if ($deposit->depositable_type == PlanPurchase::class) {
                    $purchase = $deposit->depositable;
                    if (isset($purchase)) {
                        $this->purchaseCompleteAction($purchase, $deposit);
                    }
                } elseif ($deposit->depositable_type == Booking::class) {
                    $booking = $deposit->depositable;
                    if (isset($booking)) {
                        $this->bookingCompleteAction($booking, $deposit);
                    }
                }
                return true;
            }
        } catch (\Exception $e) {
        }
    }

    public function bookingCompleteAction($purchase, $deposit)
    {
        $purchase->status = 5; //Pending Tour
        $purchase->save();

        if (getGatewayModel($purchase->package) == Gateway::class) {
            $owner = $purchase->package->owner;

            if ($owner) {
                $owner->balance += $deposit->payable_amount_in_base_currency;
                $owner->save();
            }
        }

        $purchase->package->increment('total_sell');


        $remark = 'Payment via ' . $deposit->gatewayable->name . 'for tour booking';
        $this->makeTransaction($purchase->user_id, $deposit->user->balance, $deposit->payable_amount_in_base_currency, '-', $remark, $purchase->id, Booking::class, $deposit->base_currency_charge, $deposit->vendor_id);


        $params = [
            'package' => $purchase->package?->title,
            'amount' => currencyPosition($deposit->payable_amount_in_base_currency),
            'transaction' => $deposit->trx_id,
        ];

        $action = [
            "link" => route('user.transaction'),
            "icon" => "fa fa-money-bill-alt text-white"
        ];
        $firebaseAction = '#';
        $this->sendMailSms($deposit->user, 'BOOKING_PAYMENT', $params);
        $this->userPushNotification($deposit->user, 'BOOKING_PAYMENT', $params, $action);
        $this->userFirebasePushNotification($deposit->user, 'BOOKING_PAYMENT', $params, $firebaseAction);

        $vendoraction = [
            "link" => route('user.vendor.booking.list'),
            "icon" => "fa fa-money-bill-alt text-white"
        ];

        $this->sendMailSms($purchase->package->owner, 'BOOKING_PAYMENT_OWNER', $params);
        $this->userPushNotification($purchase->package->owner, 'BOOKING_PAYMENT_OWNER', $params, $vendoraction);
        $this->userFirebasePushNotification($purchase->package->owner, 'BOOKING_PAYMENT_OWNER', $params);

        $params = [
            'package' => $purchase->package?->title,
            'username' => optional($deposit->user)->username,
            'amount' => currencyPosition($deposit->payable_amount_in_base_currency),
            'transaction' => $deposit->trx_id,
        ];
        $actionAdmin = [
            "name" => optional($deposit->user)->firstname . ' ' . optional($deposit->user)->lastname,
            "image" => getFile(optional($deposit->user)->image_driver, optional($deposit->user)->image),
            "link" => route('admin.all.booking'),
            "icon" => "fas fa-ticket-alt text-white"
        ];

        $this->adminMail('TOUR_BOOKING_PAYMENT', $params, $action);
        $this->adminPushNotification('TOUR_BOOKING_PAYMENT', $params, $actionAdmin);
        $this->adminFirebasePushNotification('TOUR_BOOKING_PAYMENT', $params);
    }

    public function purchaseCompleteAction($purchase, $deposit)
    {
        $purchase->status = 1;
        $purchase->save();

        $vendorInfo = $deposit->user->vendorInfo;

        $vendorInfo->active_plan = $purchase->plan_id;
        $vendorInfo->current_plan_purchase_date = $purchase->created_at;
        $vendorInfo->current_plan_expiry_date = $purchase->expiry_date;
        $vendorInfo->current_plan_posted_listing = 0;
        $vendorInfo->save();

        if (isset($deposit->user->packages)) {
            foreach ($deposit->user->packages as $package) {
                if ($package->status == 3) {
                    $package->status = 1;
                }
            }
        }

        $remark = 'Payment via ' . $deposit->gatewayable->name . 'for plan purchase';
        $this->makeTransaction($purchase->user_id, $deposit->user->balance, $deposit->payable_amount_in_base_currency, '-', $remark, $purchase->id, PlanPurchase::class, $deposit->base_currency_charge);


        $params = [
            'amount' => currencyPosition($deposit->payable_amount_in_base_currency),
            'transaction' => $deposit->trx_id,
        ];

        $action = [
            "link" => route('user.transaction'),
            "icon" => "fa fa-money-bill-alt text-white"
        ];
        $firebaseAction = '#';
        $this->sendMailSms($deposit->user, 'PLAN_PURCHASE', $params);
        $this->userPushNotification($deposit->user, 'PLAN_PURCHASE', $params, $action);
        $this->userFirebasePushNotification($deposit->user, 'PLAN_PURCHASE', $params, $firebaseAction);

        $params = [
            'username' => optional($deposit->user)->username,
            'amount' => currencyPosition($deposit->payable_amount_in_base_currency),
            'transaction' => $deposit->trx_id,
        ];
        $actionAdmin = [
            "name" => optional($deposit->user)->firstname . ' ' . optional($deposit->user)->lastname,
            "image" => getFile(optional($deposit->user)->image_driver, optional($deposit->user)->image),
            "link" => route('admin.plan.sold.list'),
            "icon" => "fas fa-ticket-alt text-white"
        ];

        $firebaseAction = "#";
        $this->adminMail('VENDOR_PLAN_PURCHASE', $params, $action);
        $this->adminPushNotification('VENDOR_PLAN_PURCHASE', $params, $actionAdmin);
        $this->adminFirebasePushNotification('VENDOR_PLAN_PURCHASE', $params, $firebaseAction);
    }

    public function makeTransaction($userId, $userBalance, $amount, $trxType, $remark, $transactionalId = null, $transactionalType = null, $charge_in_base_currency = null, $vendor_id = null): void
    {
        $transaction = new Transaction();
        $transaction->user_id = $userId;
        $transaction->amount = $amount;
        $transaction->charge = $charge_in_base_currency;
        $transaction->balance = $userBalance;
        $transaction->trx_type = $trxType;
        $transaction->remarks = $remark;
        $transaction->vendor_id = $vendor_id;;
        $transaction->transactional_id = $transactionalId;
        $transaction->transactional_type = $transactionalType;
        $transaction->save();


    }


    public function cryptoQR($wallet, $amount, $crypto = null)
    {
        $varb = $wallet . "?amount=" . $amount;
        return "https://quickchart.io/chart?cht=qr&chs=150x150&chl=$varb";
//        return "https://quickchart.io/chart?cht=qr&chl=$cryptoQr";
    }

}
