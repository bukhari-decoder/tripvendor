<?php

namespace App\Traits;

use App\Models\Blog;
use App\Models\City;
use App\Models\ContentDetails;
use App\Models\Destination;
use App\Models\Package;
use App\Models\PackageCategory;
use App\Models\Plan;

trait Frontend
{
    protected function getSectionsData($sections, $content, $selectedTheme)
    {
        if ($sections == null) {
            $data = ['support' => $content,];
            return view("themes.$selectedTheme.support", $data)->toHtml();
        }

        $contentData = ContentDetails::with('content')
            ->whereHas('content', function ($query) use ($sections) {
                $query->whereIn('name', $sections);
            })
            ->get();

        foreach ($sections as $section) {
            $singleContent = $contentData->where('content.name', $section)->where('content.type', 'single')->first() ?? [];

            $multipleContents = $contentData->where('content.name', $section)->where('content.type', 'multiple')->values()->map(function ($multipleContentData) {
                return collect($multipleContentData->description)->merge($multipleContentData->content->only('media'));
            });

            $data[$section] = [
                'single' => $singleContent ? collect($singleContent->description ?? [])->merge($singleContent->content->only('media')) : [],
                'multiple' => $multipleContents
            ];

            $data['trending_destinations'] = $this->getTrendingDestinationData($section, $singleContent, $multipleContents);
            $data['destination_one'] = $this->getDestinationOneData($section, $singleContent, $multipleContents);
            $data['destination_two'] = $this->getDestinationTwoData($section, $singleContent, $multipleContents);
            $data['news_one'] = $this->getNewsOneData($section, $singleContent, $multipleContents);
            $data['news_two'] = $this->getNewsTwoData($section, $singleContent, $multipleContents);
            $data['news_three'] = $this->getNewsThreeData($section, $singleContent, $multipleContents);
            $data['plans'] = $this->getPlansData($section, $singleContent, $multipleContents);
            $data['tour_one'] = $this->getTourOneData($section, $singleContent, $multipleContents);
            $data['tour_discover'] = $this->getTourDiscoverData($section, $singleContent, $multipleContents);
            $data['hero_one'] = $this->getHeroOneData($section, $singleContent, $multipleContents);
            $data['hero_two'] = $this->getHeroTwoData($section, $singleContent, $multipleContents);
            $data['hero_three'] = $this->getHeroThreeData($section, $singleContent, $multipleContents);
            $data['top_destination'] = $this->getTopDestinationData($section, $singleContent, $multipleContents);
            $data['tour_places'] = $this->getTourPlacesData($section, $singleContent, $multipleContents);
            $data['tour_two'] = $this->getTourTwoData($section, $singleContent, $multipleContents);
            $data['tour_three'] = $this->getTourThreeData($section, $singleContent, $multipleContents);

            $replacement = view("themes.{$selectedTheme}.sections.{$section}", $data)->toHtml();

            $content = str_replace('<div class="custom-block" contenteditable="false"><div class="custom-block-content">[[' . $section . ']]</div>', $replacement, $content);
            $content = str_replace('<span class="delete-block">×</span>', '', $content);
            $content = str_replace('<span class="up-block">↑</span>', '', $content);
            $content = str_replace('<span class="down-block">↓</span></div>', '', $content);
            $content = str_replace('<p><br></p>', '', $content);
        }

        return $content;
    }
    public function getTrendingDestinationData($section, $singleContent, $multipleContents)
    {
        $trending_destinations_Data = [];
        if ($section == 'trending_destinations') {
            $trending_destinations_Data = \Cache::get("trending_destinations_Data");
            if (!$trending_destinations_Data || $trending_destinations_Data->isEmpty()) {
                $trending_destinations_Data = Destination::where('status', 1)->orderBy('total_visited', 'desc')->latest()->take(6)->get();
                \Cache::put('trending_destinations_Data', $trending_destinations_Data);
            }

            $single = $singleContent ? collect($singleContent->description ?? [])->merge($singleContent->content->only('media')) : [];
            $multiple = $multipleContents;

            return [
                'destinations' => $trending_destinations_Data,
                'single' => $single,
                'multiple' => $multiple
            ];
        }
    }
    public function getDestinationOneData($section, $singleContent, $multipleContents)
    {
        $destination_one_Data = [];
        if ($section == 'destination_one') {
            $destination_one_Data = \Cache::get("destination_one_Data");
            if (!$destination_one_Data || $destination_one_Data->isEmpty()) {
                $destination_one_Data = City::with(['state:id,name','country:id,name,image,image_driver'])
                    ->withCount('destinations')
                    ->where('status', 1)
                    ->orderByDesc('destinations_count')
                    ->latest()
                    ->take(5)
                    ->get();
                \Cache::put('destination_one_Data', $destination_one_Data);
            }

            $single = $singleContent ? collect($singleContent->description ?? [])->merge($singleContent->content->only('media')) : [];
            $multiple = $multipleContents;

            return [
                'destinations' => $destination_one_Data,
                'single' => $single,
                'multiple' => $multiple
            ];
        }
    }
    public function getDestinationTwoData($section, $singleContent, $multipleContents)
    {
        $destination_two_Data = [];
        if ($section == 'destination_two') {
            $destination_two_Data = \Cache::get("destination_two_Data");
            if (!$destination_two_Data || $destination_two_Data->isEmpty()) {
                $destination_two_Data = Destination::where('status', 1)->latest()->get();
                \Cache::put('destination_two_Data', $destination_two_Data);
            }

            $single = $singleContent ? collect($singleContent->description ?? [])->merge($singleContent->content->only('media')) : [];
            $multiple = $multipleContents;

            return [
                'destinations' => $destination_two_Data,
                'single' => $single,
                'multiple' => $multiple
            ];
        }
    }
    public function getNewsOneData($section, $singleContent, $multipleContents)
    {
        $news_one_Data = [];
        if ($section == 'news_one') {
            $news_one_Data = \Cache::get("news_one_Data");
            if (!$news_one_Data || $news_one_Data->isEmpty()) {
                $news_one_Data = Blog::with(['details','comments'])->withCount('comments')->where('status', 1)->latest()->take(3)->get();
                \Cache::put('news_one_Data', $news_one_Data);
            }

            $single = $singleContent ? collect($singleContent->description ?? [])->merge($singleContent->content->only('media')) : [];
            $multiple = $multipleContents;

            return [
                'blogs' => $news_one_Data,
                'single' => $single,
                'multiple' => $multiple
            ];
        }
    }
    public function getNewsTwoData($section, $singleContent, $multipleContents)
    {
        $news_two_Data = [];
        if ($section == 'news_two') {
            $news_two_Data = \Cache::get("news_two_Data");
            if (!$news_two_Data || $news_two_Data->isEmpty()) {
                $news_two_Data = Blog::with(['details','comments'])->withCount('comments')->where('status', 1)->latest()->take(3)->get();
                \Cache::put('news_two_Data', $news_two_Data);
            }


            $single = $singleContent ? collect($singleContent->description ?? [])->merge($singleContent->content->only('media')) : [];
            $multiple = $multipleContents;

            return [
                'blogs' => $news_two_Data,
                'single' => $single,
                'multiple' => $multiple,
            ];
        }
    }
    public function getPlansData($section, $singleContent, $multipleContents)
    {
        $plans_Data = [];
        if ($section == 'plans') {

            $plans_Data = Plan::where('status', 1)->orderBy('sort_by','asc')->get();


            $single = $singleContent ? collect($singleContent->description ?? [])->merge($singleContent->content->only('media')) : [];
            $multiple = $multipleContents;

            return [
                'planList' => $plans_Data,
                'single' => $single,
                'multiple' => $multiple,
            ];
        }
    }
    public function getTourOneData($section, $singleContent, $multipleContents)
    {
        $tour_one_Data = [];
        if ($section == 'tour_one') {
            $tour_one_Data = \Cache::get("tour_one_Data");
            if (!$tour_one_Data || $tour_one_Data->isEmpty()) {
                $tour_one_Data = Package::with(['countryTake:id,name', 'stateTake:id,name', 'cityTake:id,name'])->orderByRaw("CASE
                    WHEN is_featured = 1 THEN 0
                    ELSE 1
                END")
                    ->where('status', 1)->latest()->take(8)->get();
                \Cache::put('tour_one_Data', $tour_one_Data);
            }


            $single = $singleContent ? collect($singleContent->description ?? [])->merge($singleContent->content->only('media')) : [];
            $multiple = $multipleContents;

            return [
                'packages' => $tour_one_Data,
                'single' => $single,
                'multiple' => $multiple,
            ];
        }
    }
    public function getNewsThreeData($section, $singleContent, $multipleContents)
    {
        $news_three_Data = [];
        if ($section == 'news_three') {
            $news_three_Data = \Cache::get("news_three_Data");
            if (!$news_three_Data || $news_three_Data->isEmpty()) {
                $news_three_Data = Blog::with(['details','category'])->where('status', 1)->latest()->take(3)->get();
                \Cache::put('news_three_Data', $news_three_Data);
            }
            $brandSection = 'brand_one';
            $contentData = ContentDetails::with('content')
                ->whereHas('content', function ($query) use ($brandSection) {
                    $query->where('name', $brandSection);
                })
                ->get();
            $BrandMulti = $contentData->where('content.name', $brandSection)->where('content.type', 'multiple')->values()->map(function ($BrandMulti) {
                return collect($BrandMulti->description)->merge($BrandMulti->content->only('media'));
            });

            $single = $singleContent ? collect($singleContent->description ?? [])->merge($singleContent->content->only('media')) : [];
            $multiple = $multipleContents;

            return [
                'blogs' => $news_three_Data,
                'single' => $single,
                'multiple' => $multiple,
                'brands' => $BrandMulti,
            ];
        }
    }
    public function getTourDiscoverData($section, $singleContent, $multipleContents)
    {
        $tour_discover_Data = [];
        if ($section == 'tour_discover') {
            $tour_discover_Data = \Cache::get("tour_discover_Data");
            if (!$tour_discover_Data || $tour_discover_Data->isEmpty()) {
                $tour_discover_Data = Package::with(['reviews','countryTake:id,name', 'stateTake:id,name', 'cityTake:id,name'])
                    ->withCount('reviews')
                    ->orderByRaw("CASE
                    WHEN is_featured = 1 THEN 0
                    ELSE 1
                END")
                    ->where('status', 1)
                    ->where('discount', 1)
                    ->latest()->take(8)
                    ->get();
                \Cache::put('tour_discover_Data', $tour_discover_Data);
            }


            $single = $singleContent ? collect($singleContent->description ?? [])->merge($singleContent->content->only('media')) : [];
            $multiple = $multipleContents;

            return [
                'packages' => $tour_discover_Data,
                'single' => $single,
                'multiple' => $multiple,
            ];
        }
    }
    public function getHeroOneData($section, $singleContent, $multipleContents)
    {
        $hero_one_destination = [];
        if ($section == 'hero_one') {
            $hero_one_destination = \Cache::get("hero_one_destination");
            if (!$hero_one_destination || $hero_one_destination->isEmpty()) {
                $hero_one_destination = Destination::with(['countryTake:id,name', 'stateTake:id,name', 'cityTake:id,name'])->where('status', 1)->latest()->get();
                \Cache::put('hero_one_destination', $hero_one_destination);
            }

            $durations = Package::where('status', 1)->pluck('duration')->unique();
            $time_slot = Package::where('status', 1)
                ->pluck('timeSlot')
                ->flatMap(function ($item) {
                    if (is_string($item)) {
                        return json_decode($item, true) ?? [];
                    } elseif (is_array($item)) {
                        return $item;
                    }
                    return [];
                })
                ->unique()
                ->values();

            $single = $singleContent ? collect($singleContent->description ?? [])->merge($singleContent->content->only('media')) : [];
            $multiple = $multipleContents;

            return [
                'destination' => $hero_one_destination,
                'durations' => $durations,
                'slots' => $time_slot,
                'single' => $single,
                'multiple' => $multiple,
            ];
        }
    }
    public function getHeroTwoData($section, $singleContent, $multipleContents)
    {
        $hero_two_destination = [];
        if ($section == 'hero_two') {
            $hero_two_destination = \Cache::get("hero_two_destination");
            if (!$hero_two_destination || $hero_two_destination->isEmpty()) {
                $hero_two_destination = Destination::with(['countryTake:id,name', 'stateTake:id,name', 'cityTake:id,name'])->where('status', 1)->latest()->get();
                \Cache::put('hero_two_destination', $hero_two_destination);
            }

            $durations = Package::where('status', 1)->pluck('duration')->unique();
            $time_slot = Package::where('status', 1)
                ->pluck('timeSlot')
                ->flatMap(function ($item) {
                    if (is_string($item)) {
                        return json_decode($item, true) ?? [];
                    } elseif (is_array($item)) {
                        return $item;
                    }
                    return [];
                })
                ->unique()
                ->values();

            $single = $singleContent ? collect($singleContent->description ?? [])->merge($singleContent->content->only('media')) : [];
            $multiple = $multipleContents;

            return [
                'destination' => $hero_two_destination,
                'durations' => $durations,
                'slots' => $time_slot,
                'single' => $single,
                'multiple' => $multiple,
            ];
        }
    }
    public function getHeroThreeData($section, $singleContent, $multipleContents)
    {
        $tour_three_destination = [];
        if ($section == 'hero_three') {
            $tour_three_destination = \Cache::get("hero_three_destination");
            if (!$tour_three_destination || $tour_three_destination->isEmpty()) {
                $tour_three_destination = Destination::with(['countryTake:id,name', 'stateTake:id,name', 'cityTake:id,name'])->where('status', 1)->latest()->get();
                \Cache::put('hero_three_destination', $tour_three_destination);
            }

            $durations = Package::where('status', 1)->pluck('duration')->unique();
            $time_slot = Package::where('status', 1)
                ->pluck('timeSlot')
                ->flatMap(function ($item) {
                    if (is_string($item)) {
                        return json_decode($item, true) ?? [];
                    } elseif (is_array($item)) {
                        return $item;
                    }
                    return [];
                })
                ->unique()
                ->values();

            $single = $singleContent ? collect($singleContent->description ?? [])->merge($singleContent->content->only('media')) : [];
            $multiple = $multipleContents;

            return [
                'destination' => $tour_three_destination,
                'durations' => $durations,
                'slots' => $time_slot,
                'single' => $single,
                'multiple' => $multiple,
            ];
        }
    }
    public function getTopDestinationData($section, $singleContent, $multipleContents)
    {
        $top_destination_Data = [];
        if ($section == 'top_destination') {
            $top_destination_Data = \Cache::get("top_destination_Data");
            if (!$top_destination_Data || $top_destination_Data->isEmpty()) {
                $top_destination_Data = PackageCategory::with(['packages'])
                    ->withCount('packages')
                    ->where('status', 1)
                    ->select('package_categories.*')
                    ->addSelect([
                        'packages_count' => Package::selectRaw('COUNT(*)')
                            ->whereColumn('packages.package_category_id', 'package_categories.id'),
                        'min_adult_price' => Package::selectRaw('MIN(adult_price)')
                            ->whereColumn('packages.package_category_id', 'package_categories.id')
                    ])
                    ->latest()
                    ->take(8)
                    ->get();

                \Cache::put('top_destination_Data', $top_destination_Data);
            }


            $single = $singleContent ? collect($singleContent->description ?? [])->merge($singleContent->content->only('media')) : [];
            $multiple = $multipleContents;

            return [
                'categories' => $top_destination_Data,
                'single' => $single,
                'multiple' => $multiple,
            ];
        }
    }
    public function getTourPlacesData($section, $singleContent, $multipleContents)
    {
        $tour_places_Data = [];
        if ($section == 'tour_places') {
            $tour_places_Data = \Cache::get("tour_places_Data");
            if (!$tour_places_Data || $tour_places_Data->isEmpty()) {
                $tour_places_Data = Package::with(['reviews','countryTake:id,name'])
                    ->withCount('reviews')
                    ->where('status', 1)
                    ->where('is_featured', 1)
                    ->orderBy('total_sell', 'desc')
                    ->take(3)
                    ->get();

                \Cache::put('tour_places_Data', $tour_places_Data);
            }


            $single = $singleContent ? collect($singleContent->description ?? [])->merge($singleContent->content->only('media')) : [];
            $multiple = $multipleContents;

            return [
                'places' => $tour_places_Data,
                'single' => $single,
                'multiple' => $multiple,
            ];
        }
    }
    public function getTourTwoData($section, $singleContent, $multipleContents)
    {
        $tour_two_Data = [];
        if ($section == 'tour_two') {
            $tour_two_Data = \Cache::get("tour_two_Data");
            if (!$tour_two_Data || $tour_two_Data->isEmpty()) {
                $tour_two_Data = Package::with(['reviews','countryTake:id,name'])
                    ->withCount('reviews')
                    ->where('status', 1)
                    ->orderByRaw("CASE
                        WHEN is_featured = 1 THEN 0
                            ELSE 1
                        END")
                    ->where('discount', 1)
                    ->take(10)
                    ->get();

                \Cache::put('tour_two_Data', $tour_two_Data);
            }


            $single = $singleContent ? collect($singleContent->description ?? [])->merge($singleContent->content->only('media')) : [];
            $multiple = $multipleContents;

            return [
                'packages' => $tour_two_Data,
                'single' => $single,
                'multiple' => $multiple,
            ];
        }
    }
    public function getTourThreeData($section, $singleContent, $multipleContents)
    {
        $tour_three_Data = [];
        if ($section == 'tour_three') {
            $tour_three_Data = \Cache::get("tour_three_Data");
            if (!$tour_three_Data || $tour_three_Data->isEmpty()) {
                $tour_three_Data = Package::with(['reviews', 'countryTake:id,name'])
                    ->withCount('reviews')
                    ->where('status', 1)
                    ->orderByRaw("CASE WHEN is_featured = 1 THEN 0 ELSE 1 END")
                    ->take(6)
                    ->get();

                \Cache::put('tour_three_Data', $tour_three_Data);
            }


            $single = $singleContent ? collect($singleContent->description ?? [])->merge($singleContent->content->only('media')) : [];
            $multiple = $multipleContents;

            return [
                'packages' => $tour_three_Data,
                'single' => $single,
                'multiple' => $multiple,
            ];
        }
    }
}
