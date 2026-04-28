<?php


namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

trait WebhookNotify
{
    public function pushStatusUserWebhook($email, $event, $user = null): void
    {
        if ($user && $user->webhook_url) {
            foreach ($user->webhook_url as $url) {
                Http::get($url->url, [
                    'email' => $email,
                    'event' => $event,
                    'timestamp' => Carbon::now()->toDateTimeString(),
                ]);
            }
        }
    }
}
