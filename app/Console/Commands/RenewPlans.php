<?php

namespace App\Console\Commands;

use App\Models\Plan;
use App\Models\PlanPurchase;
use App\Models\Transaction;
use App\Models\User;
use App\Traits\Notify;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RenewPlans extends Command
{
    use Notify;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'renew:plans';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Plan Auto Renew';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tomorrow = now()->addDay()->toDateString();
        $users = User::with(['vendorInfo', 'packages'])
            ->where('status', 1)
            ->where('role', 1)
            ->whereHas('vendorInfo')
            ->get();

        foreach ($users as $user) {
            try {
                if (isset($user->deleted_at) && $user->deleted_at !== null) {
                    $deletedAt = Carbon::parse($user->deleted_at);

                    if ($deletedAt->diffInDays(Carbon::now()) > 15) {
                        $user->forceDelete();
                    }
                } else {
                    if (isset($user->vendorInfo->reminder_sent_at)) {
                        $reminderDate = Carbon::parse($user->vendorInfo->reminder_sent_at)->startOfDay();
                        $twoDaysAgo = Carbon::today()->subDays(2);

                        if ($reminderDate->lte($twoDaysAgo)) {
                            if (isset($user->packages)) {
                                foreach ($user->packages as $package) {
                                    $package->status = 3;
                                    $package->save();
                                }

                                $params = [
                                    'username' => $user->firstname . ' ' . $user->lastname,
                                    'message' => 'Reminder: Your plan expires today, so your packages are currently not visible on the site. Renew to get back online!',
                                ];

                                $action = [
                                    "link" => route('page', 'plans'),
                                    "icon" => "fa fa-money-bill-alt text-white"
                                ];

                                $firebaseAction = '#';
                                $this->sendMailSms($user, 'PLAN_EXPIRED', $params);
                                $this->userPushNotification($user, 'PLAN_EXPIRED', $params, $action);
                                $this->userFirebasePushNotification($user, 'PLAN_EXPIRED', $params, $firebaseAction);
                            }
                        }
                    } else {
                        if ($user->vendorInfo && $user->vendorInfo->current_plan_expiry_date == $tomorrow) {
                            if ($user->vendorInfo->auto_renew_current_plan == 1) {

                                DB::beginTransaction();

                                try {
                                    $currenctPlan = Plan::where('id', $user->vendorInfo->active_plan)
                                        ->where('status', 1)
                                        ->first();

                                    if ($currenctPlan) {
                                        if ($currenctPlan->price <= $user->balance) {

                                            switch ($currenctPlan->validity_type) {
                                                case 'daily':
                                                    $expiryDate = now()->addDays($currenctPlan->validity)->toDateString();
                                                    break;
                                                case 'weekly':
                                                    $expiryDate = now()->addWeeks($currenctPlan->validity)->toDateString();
                                                    break;
                                                case 'monthly':
                                                    $expiryDate = now()->addMonths($currenctPlan->validity)->toDateString();
                                                    break;
                                                case 'yearly':
                                                    $expiryDate = now()->addYears($currenctPlan->validity)->toDateString();
                                                    break;
                                                default:
                                                    $expiryDate = now()->toDateString();
                                                    break;
                                            }

                                            $user->balance -= $currenctPlan->price;
                                            $user->save();

                                            $transaction = new Transaction();
                                            $transaction->user_id = $user->id;
                                            $transaction->amount = $currenctPlan->price;
                                            $transaction->charge = 0;
                                            $transaction->balance = $user->balance;
                                            $transaction->trx_type = '-';
                                            $transaction->remarks = 'Plan Auto renewed via balance';
                                            $transaction->transactional_id = optional($user->lastPurchasedPlan)->id;
                                            $transaction->transactional_type = PlanPurchase::class;
                                            $transaction->save();

                                            $user->vendorInfo->current_plan_purchase_date = now()->toDateString();
                                            $user->vendorInfo->current_plan_expiry_date = $expiryDate;
                                            $user->vendorInfo->save();

                                            if ($user->lastPurchasedPlan) {
                                                $user->lastPurchasedPlan->expiry_date = $expiryDate;
                                                $user->lastPurchasedPlan->save();
                                            }

                                            $params = [
                                                'username' => $user->firstname . ' ' . $user->lastname,
                                                'amount' => currencyPosition($currenctPlan->price),
                                                'expiry_date' => dateTime($user->vendorInfo->current_plan_expiry_date),
                                                'transaction' => $transaction->trx_id,
                                            ];

                                            $action = [
                                                "link" => route('user.transaction'),
                                                "icon" => "fa fa-money-bill-alt text-white"
                                            ];
                                            $firebaseAction = '#';

                                            $this->sendMailSms($user, 'PLAN_RENEWED', $params);
                                            $this->userPushNotification($user, 'PLAN_RENEWED', $params, $action);
                                            $this->userFirebasePushNotification($user, 'PLAN_RENEWED', $params, $firebaseAction);

                                        } else {
                                            $user->vendorInfo->reminder_sent_at = now();
                                            $user->vendorInfo->save();

                                            $params = [
                                                'username' => $user->firstname . ' ' . $user->lastname,
                                                'message' => 'Heads up! Your plan expires tomorrow. Renew now to keep your listings visible on the site.',
                                            ];

                                            $action = [
                                                "link" => route('page', 'plans'),
                                                "icon" => "fa fa-money-bill-alt text-white"
                                            ];
                                            $firebaseAction = '#';

                                            $this->sendMailSms($user, 'PLAN_EXPIRED_TOMORROW', $params);
                                            $this->userPushNotification($user, 'PLAN_EXPIRED_TOMORROW', $params, $action);
                                            $this->userFirebasePushNotification($user, 'PLAN_EXPIRED_TOMORROW', $params, $firebaseAction);
                                        }

                                    }
                                    DB::commit();
                                } catch (\Exception $e) {
                                    DB::rollBack();
                                }
                            }
                        }
                    }
                }
            } catch (\Exception $exception) {
                continue;
            }
        }
    }
}
