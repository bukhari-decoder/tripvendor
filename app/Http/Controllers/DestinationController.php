<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\Destination;
use App\Models\Package;
use App\Models\Page;
use Illuminate\Http\Request;

class DestinationController extends Controller
{
    public function destinationList(Request $request)
    {
        try {
            $seoData = Page::where('name', 'destination')->select(['page_title','meta_title','meta_keywords','meta_description','og_description','meta_robots','meta_image_driver','meta_image','breadcrumb_status','breadcrumb_image','breadcrumb_image_driver'])->first();

            $data['pageSeo'] = [
                'page_title' => $seoData->page_title ?? '',
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


            $data['destinations'] = Destination::with(['packages', 'countryTake:id,name'])
                ->withCount('packages')
                ->orderBy('packages_count', 'DESC')
                ->when(request('search'), function ($query, $search) {
                    return $query->where('title', 'like', '%' . $search . '%');
                })
                ->when(request('category'), function ($query, $category) {
                    return $query->where('destination_category_id', $category);
                })
                ->where('status', 1)
                ->paginate(12);

            return view(template() . 'frontend.destinations.list', $data);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function destinationDetails($slug){
        try {

            $seoData = Page::where('name', 'destination')->select(['page_title','meta_title','meta_keywords','meta_description','og_description','meta_robots','meta_image_driver','meta_image','breadcrumb_status','breadcrumb_image','breadcrumb_image_driver'])->first();

            $data['pageSeo'] = [
                'page_title' =>  'Destination Details' ?? $seoData->page_title ,
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

            $data['destination'] = Destination::with( ['countryTake:id,name','stateTake:id,name','cityTake:id,name'])->where('slug', $slug)->firstOr(function () {
                throw new \Exception('This Destination is not available now');
            });

            $missingParts = [];

            if (!$data['destination']->countryTake) {
                $missingParts[] = 'Country';
            }
            if (!$data['destination']->stateTake) {
                $missingParts[] = 'State';
            }
            if (!$data['destination']->cityTake) {
                $missingParts[] = 'City';
            }

            if (!empty($missingParts)) {
                throw new \Exception(implode(', ', $missingParts) . ' information is missing for this destination.');
            }

            return view(template() . 'frontend.destinations.details', $data);
        }catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
