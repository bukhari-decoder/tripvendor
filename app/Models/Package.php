<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Package extends Model
{
    use HasFactory;
    protected $guarded =['id'];

    protected $casts = [
        'facility' => 'object',
        'excluded' => 'object',
        'expected' => 'object',
        'amenities' => 'object',
        'places' => 'object',
        'guides' => 'object',
        'timeSlot' => 'object',
        'rating' => 'object',
        'meta_keywords' => 'array',
        'imagesUrl' => 'array',
    ];

    public function reviewSummary()
    {
        return $this->hasOne(Review::class, 'package_id')
            ->selectRaw('package_id, AVG(avg_rating) as average_rating, COUNT(*) as review_count')
            ->groupBy('package_id');
    }

    public function getImagesUrlAttribute($value)
    {
        return $value ? json_decode($value, true) : [];
    }

    public function guides()
    {
        return Guide::whereIn('code', $this->guides ?? []);
    }
    public function activities()
    {
        return $this->hasMany(ActivityLog::class, 'property_id')->latest();
    }

    public function latestActivity()
    {
        return $this->hasOne(ActivityLog::class, 'property_id')->latestOfMany(); // Laravel 8+
    }
    public function getGuideModelsAttribute()
    {
        return Guide::whereIn('code', $this->guides ?? [])->get();
    }

    public function setImagesUrlAttribute($value)
    {
        $this->attributes['imagesUrl'] = json_encode($value);
    }

    protected function metaKeywords(): Attribute
    {
        return Attribute::make(
            get: fn(string $value) => explode(", " , $value),
        );
    }

    public function metaRobots()
    {
        $cleaned = str_replace(['[', ']', '"'], '', $this->meta_robots);
        return explode(",", $cleaned);
    }

    public function getMetaRobotAttribute()
    {
        $cleaned = str_replace(['[', ']', '"'], '', $this->meta_robots);
        return $cleaned;
    }

    protected $appends = ['bookings'];

    public function booking()
    {
        return $this->hasMany(Booking::class, 'package_id');
    }

    public function getBookingsAttribute($id)
    {
        $disabledRanges = [];
        $bookings = Booking::select(['id','date', 'package_id', DB::raw('SUM(total_person) as total_person_sum')])
            ->where('package_id', $id)
            ->groupBy(['date'])
            ->get();

        $packages = Package::whereIn('id', $bookings->pluck('package_id'))->get()->keyBy('id');

        foreach ($bookings as $booking) {
            $package = $packages->get($booking->package_id);

            if ($package) {
                $totalPerson = $booking->total_person_sum;

                if ($package->maximumTravelers == $totalPerson) {
                    $disabledRanges[] = [
                        'date' => $booking->date,
                        'message' => "Don't have any space to book this tour.",
                    ];
                }
            }
        }
        return $disabledRanges;
    }
    public function getBookingsSpaceAttribute($id)
    {
        $spaceDate = [];
        $bookings = Booking::select([
            'id',
            'date',
            'package_id',
            DB::raw('SUM(total_person) as total_person_sum')
        ])
            ->where('package_id', $id)
            ->groupBy(DB::raw('DATE(date)'))
            ->get();

        foreach ($bookings as $booking) {
            $package = Package::find($booking->package_id);

            if ($package) {
                $totalPerson = $booking->total_person_sum;
                if ($package->maximumTravelers > $totalPerson) {
                    $spaceDate[] = [
                        'date' => Carbon::parse($booking->date)->toDateString(),
                        'space' => $package->maximumTravelers - $totalPerson,
                    ];
                }
            }
        }
        return $spaceDate;
    }

    public function category()
    {
        return $this->belongsTo(PackageCategory::class, 'package_category_id');
    }
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function getPlaceCountAttribute()
    {
        $places = is_array($this->places) ? $this->places : json_decode($this->places, true);

        if (json_last_error() === JSON_ERROR_NONE && is_array($places)) {
            return count($places);
        }
        return 0;
    }

    public function media()
    {
        return $this->hasMany(PackageMedia::class, 'package_id');
    }
    public function visitor()
    {
        return $this->hasMany(PackageVisitor::class, 'package_id');
    }
    public function review()
    {
        return $this->hasMany(Review::class, 'package_id')->where('status', 1)->latest();
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'package_id')->where('parent_review_id', null)->where('status', 1);
    }


    public function destination()
    {
        return $this->belongsTo(Destination::class, 'destination_id');
    }

    public function countryTake()
    {
        return $this->belongsTo(Country::class, 'country', 'id');
    }

    public function stateTake()
    {
        return $this->belongsTo(State::class, 'state', 'id');
    }

    public function cityTake()
    {
        return $this->belongsTo(City::class, 'city', 'id');
    }
    public function transactional()
    {
        return $this->morphOne(Transaction::class, 'transactional');
    }
    protected static $reviewCountCache = [];

    public function getReviewCountAttribute()
    {
        $packageId = $this->id;

        if (!isset(self::$reviewCountCache[$packageId])) {
            self::$reviewCountCache[$packageId] = $this->reviews()->count();
        }

        return self::$reviewCountCache[$packageId];
    }

    public function getReviewAverageAttribute()
    {
        return round($this->reviews()->avg('rating'), 1);
    }
    public function getFavouriteCountAttribute()
    {
        return $this->reaction()->count();
    }

    public function getVisitorCountAttribute()
    {
        return $this->visitor()->count();
    }

    public function getReviewPercentage($rating)
    {
        $reviewCount = $this->review_count;
        $count = $this->reviews()->where('rating', $rating)->count();
        return ($reviewCount > 0) ? ($count / $reviewCount) * 100 : 0;
    }
    public static function withAllRelations()
    {
        return self::with([
            'category:id,name',
            'destination:id,title,slug',
            'media',
            'owner',
            'countryTake:id,name',
            'stateTake:id,name',
            'cityTake:id,name',
            'reviews.user:id,firstname,lastname,username,image,image_driver,address_one',
            'reviews.reply.user:id,firstname,lastname,username,image,image_driver,address_one',
            'chat.reply',
        ]);
    }
    public function getBookingDates()
    {
        return $this->getBookingsAttribute($this->id);
    }
    public function getRelatedPackagesAttribute()
    {
        return $this->category->packages
            ->where('id', '!=', $this->id)->take(3) ?? collect();
    }
    public function chat()
    {
        return $this->hasMany(Chat::class, 'package_id');
    }
}
