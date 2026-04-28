<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\Notify;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, Notify;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = ['last-seen-activity', 'user_image', 'fullname'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected $dates = ['deleted_at'];

    protected static function boot()
    {
        parent::boot();
        static::saved(function () {
            Cache::forget('userRecord');
        });
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class)->latest();
    }

    public function planPurchased()
    {
        return $this->hasMany(PlanPurchase::class, 'user_id');
    }

    public function lastPurchasedPlan()
    {
        return $this->hasMany(PlanPurchase::class, 'user_id')->latest()->first();
    }


    public function getLastSeenActivityAttribute()
    {
        if (Cache::has('user-is-online-' . $this->id) == true) {
            return true;
        } else {
            return false;
        }
    }

    public function getUserImageAttribute()
    {
        return getFile($this->image_driver, $this->image);
    }

    public function getFullnameAttribute()
    {
        return $this->firstname . ' ' . $this->lastname;
    }


    public function inAppNotification()
    {
        return $this->morphOne(InAppNotification::class, 'inAppNotificationable', 'in_app_notificationable_type', 'in_app_notificationable_id');
    }

    public function fireBaseToken()
    {
        return $this->morphMany(FireBaseToken::class, 'tokenable');
    }


    public function profilePicture()
    {
        $activeStatus = $this->LastSeenActivity === false ? 'warning' : 'success';
        $firstName = $this->firstname;
        $firstLetter = $this->firstLetter($firstName);
        if (!$this->image) {
            return $this->getInitialsAvatar($firstLetter, $activeStatus);
        } else {
            $url = getFile($this->image_driver, $this->image);
            return $this->getImageAvatar($url, $activeStatus);
        }
    }

    protected function firstLetter($firstName)
    {
        if (is_string($firstName)) {
            $firstName = mb_convert_encoding($firstName, 'UTF-8', 'auto');
        } else {
            $firstName = '';
        }
        $firstLetter = !empty($firstName) ? substr($firstName, 0, 1) : '';

        if (!mb_check_encoding($firstLetter, 'UTF-8')) {
            $firstLetter = '';
        }
        return $firstLetter;
    }

    private function getInitialsAvatar($initial, $activeStatus)
    {
        return <<<HTML
                <div class="avatar avatar-sm avatar-soft-primary avatar-circle">
                    <span class="avatar-initials">{$initial}</span>
                    <span class="avatar-status avatar-sm-status avatar-status-{$activeStatus}"></span>
                </div>
                HTML;
    }

    private function getImageAvatar($url, $activeStatus)
    {
        return <<<HTML
            <div class="avatar avatar-sm avatar-circle">
                <img class="avatar-img" src="{$url}" alt="Image Description">
                <span class="avatar-status avatar-sm-status avatar-status-{$activeStatus}"></span>
            </div>
            HTML;
    }

    public function sendPasswordResetNotification($token)
    {
        $this->mail($this, 'PASSWORD_RESET', $params = [
            'message' => '<a href="' . url('password/reset', $token) . '?email=' . $this->email . '" target="_blank">Click To Reset Password</a>'
        ]);
    }

    public function booking()
    {
        return $this->hasMany(Booking::class, 'user_id')->whereIn('status', [1, 2, 3, 4, 5]);
    }

    public function forReviewBooking()
    {
        return Booking::where('user_id', $this->id)->whereIn('status', [1, 2]);
    }

    public function forVendorReviewBooking($vendorId)
    {
        return Booking::where('user_id', $this->id)->whereIn('status', [1, 2])->whereHas('package', function ($query) use ($vendorId) {
            $query->whereIn('owner_id', $vendorId);
        });
    }

    public function vendorBooking()
    {
        return $this->hasMany(Booking::class)
            ->whereHas('package', function ($query) {
                $query->where('owner_id', $this->id);
            })
            ->whereIn('status', [1, 2, 3, 4, 5]);
    }
    public function activities()
    {
        return $this->morphMany(ActivityLog::class, 'activityable');
    }

    public function packages()
    {
        return $this->hasMany(Package::class, 'owner_id');
    }

    public function featuredPackages()
    {
        return $this->hasMany(Package::class, 'owner_id')->where('is_featured', 1);
    }

    public function tickets()
    {
        return $this->hasMany(SupportTicket::class, 'user_id');
    }

    public function getBookingForPackage($packageId)
    {
        return $this->booking()
            ->where('package_id', $packageId)
            ->whereIn('status', [1, 2])
            ->first();
    }

    public function notifypermission()
    {
        return $this->morphOne(NotificationSettings::class, 'notifyable');
    }

    public function countryTake()
    {
        return $this->belongsTo(Country::class, 'country', 'id');
    }

    public function stateTake()
    {
        return $this->belongsTo(State::class, 'state', 'name');
    }

    public function vendorInfo()
    {
        return $this->hasOne(VendorInfo::class, 'vendor_id');
    }

    public function plans()
    {
        return $this->belongsTo(PlanPurchase::class, 'user_id');
    }
    public function activePlan()
    {
        return $this->hasOne(PlanPurchase::class, 'user_id')
            ->where('status', 1)
            ->where('expiry_date', '>=', now())
            ->latest('created_at');
    }


    public function guides()
    {
        return $this->hasMany(Guide::class, 'created_by');
    }

    public function reviews()
    {
        return Review::with(['reply', 'user:id,firstname,lastname,image,image_driver,address_one'])->where('vendor_id', $this->id)->where('parent_review_id', null)->where('package_id', null);
    }
}
