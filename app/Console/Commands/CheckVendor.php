<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Traits\Notify;
use Illuminate\Console\Command;

class CheckVendor extends Command
{
    use Notify;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-vendor';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Vendor Status';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = now()->toDateString();

        $users = User::whereHas('vendorInfo', function($query) use ($today) {
            $query->whereDate('current_plan_expiry_date', '=', $today);
        })->get();

        foreach ($users as $user) {
            if (isset($user->packages)){
                foreach ($user->packages as $package) {
                    $package->status = 3;
                    $package->save();
                }

                $params = [
                    'username' => $user->firstname.' '.$user->lastname,
                    'message' => 'Reminder: Your plan expires today, so your packages are currently not visible on the site. Renew to get back online!',
                ];

                $action = [
                    "link" => route('page','plans'),
                    "icon" => "fa fa-money-bill-alt text-white"
                ];

                $firebaseAction = '#';
                $this->sendMailSms($user, 'PLAN_EXPIRED', $params);
                $this->userPushNotification($user, 'PLAN_EXPIRED', $params, $action);
                $this->userFirebasePushNotification($user, 'PLAN_EXPIRED', $params, $firebaseAction);
            }
        }
    }
}
