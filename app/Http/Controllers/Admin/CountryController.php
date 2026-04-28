<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use App\Models\State;
use App\Traits\Notify;
use App\Traits\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class CountryController extends Controller
{
    use Upload,Notify;

    public function list()
    {
        try {
            $currentMonth = now()->startOfMonth();
            $nextMonth = now()->startOfMonth()->addMonth();
            $currentYear = now()->startOfYear();
            $nextYear = now()->startOfYear()->addYear();

            $allData = Country::selectRaw(
                'COUNT(*) as totalCountry,
             SUM(status = 1) as totalActiveCountry,
             SUM(status = 0) as totalInactiveCountry,
             SUM(CASE WHEN created_at >= ? AND created_at < ? THEN 1 ELSE 0 END) as totalCountryThisMonth,
             SUM(CASE WHEN created_at >= ? AND created_at < ? THEN 1 ELSE 0 END) as totalCountryThisYear',
                [$currentMonth, $nextMonth, $currentYear, $nextYear]
            )->first();

            $data['totalCountry'] = $allData->totalCountry ?? 0;
            $data['totalActiveCountry'] = $allData->totalActiveCountry ?? 0;
            $data['totalInactiveCountry'] = $allData->totalInactiveCountry ?? 0;

            $data['activeCountryPercentage'] = ($data['totalCountry'] > 0) ? ($data['totalActiveCountry'] / $data['totalCountry']) * 100 : 0;
            $data['inactiveCountryPercentage'] = ($data['totalCountry'] > 0) ? ($data['totalInactiveCountry'] / $data['totalCountry']) * 100 : 0;

            $data['totalCountryThisMonth'] = $allData->totalCountryThisMonth ?? 0;
            $data['totalCountryThisMonthPercentage'] = ($data['totalCountry'] > 0) ? ($data['totalCountryThisMonth'] / $data['totalCountry']) * 100 : 0;

            $data['totalCountryThisYear'] = $allData->totalCountryThisYear ?? 0;
            $data['totalCountryThisYearPercentage'] = ($data['totalCountry'] > 0) ? ($data['totalCountryThisYear'] / $data['totalCountry']) * 100 : 0;

            return view('admin.countries.list', $data);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function countryList(Request $request)
    {

        $countries = Country::withCount(['state','user','packages'])->when(!empty($request->search['value']), function ($query) use ($request) {
            $query->where('name', 'LIKE', '%' . $request->search['value'] . '%')
                ->orWhere('iso3', 'LIKE', '%' . $request->search['value'] . '%');
        });

        return DataTables::of($countries)
            ->addColumn('no', function () {
                static $counter = 0;
                $counter++;
                return $counter;
            })
            ->addColumn('checkbox', function ($item) {
                return ' <input type="checkbox" id="chk-' . $item->id . '"
                                       class="form-check-input row-tic tic-check" name="check" value="' . $item->id . '"
                                       data-id="' . $item->id . '">';
            })
            ->addColumn('image', function ($item) {

                $image = $item->image;
                if (!$image) {
                    $firstLetter = substr($item->name, 0, 1);
                    return '<div class="avatar avatar-sm avatar-soft-primary avatar-circle firstLetterArea">
                                <span class="avatar-initials">' . $firstLetter . '</span>
                                <span class="fs-6 text-body">' . optional($item)->name . '</span>
                            </div>';

                } else {
                    $url = getFile($item->image_driver, $item->image);
                    return '<div class="avatar avatar-sm avatar-circle">
                                <img class="avatar-img" src="' . $url . '" alt="Image Description" />
                                <span class="fs-6 text-body">' . optional($item)->name . '</span>
                            </div>
                            ';

                }
            })
            ->addColumn('short_name', function ($item) {
                return '<a class="d-flex align-items-center me-2" href="#">
                            <div class="flex-grow-1 ms-3">
                              <span class="fs-6 text-body">' . optional($item)->iso3 . '</span>
                            </div>
                        </a>';
            })
            ->addColumn('state', function ($item) {
                return ' <span class="badge bg-soft-info text-dark">' . $item->state_count . '</span>';
            })
            ->addColumn('property', function ($item) {
                return ' <span class="badge bg-soft-secondary text-dark">' . $item->packages_count . '</span>';
            })
            ->addColumn('user', function ($item) {
                return ' <span class="badge bg-soft-primary text-dark">' . $item->user_count . '</span>';
            })
            ->addColumn('status', function ($item) {
                if ($item->status == 0) {
                    return ' <span class="badge bg-soft-warning text-warning">
                                <span class="legend-indicator bg-warning"></span> ' . trans('InActive') . '
                             </span>';
                } else {
                    return '<span class="badge bg-soft-success text-success">
                                <span class="legend-indicator bg-success"></span> ' . trans('Active') . '
                            </span>';
                }
            })
            ->addColumn('action', function ($item) {

                $editUrl = route('admin.country.edit', $item->id);
                $deleteurl = route('admin.country.delete', $item->id);
                $stateList = route('admin.country.all.state', $item->id);

                return '<div class="btn-group" role="group">
                      <a href="' . $editUrl . '" class="btn btn-white btn-sm edit_user_btn">
                        <i class="bi-pencil-fill me-1"></i> ' . trans("Edit") . '
                      </a>
                    <div class="btn-group">
                      <button type="button" class="btn btn-white btn-icon btn-sm dropdown-toggle dropdown-toggle-empty" id="userEditDropdown" data-bs-toggle="dropdown" aria-expanded="false"></button>
                      <div class="dropdown-menu dropdown-menu-end mt-1" aria-labelledby="userEditDropdown">
                        <a class="dropdown-item" href="' . route("admin.users", ['country' => $item->iso2]) . '">
                           <i class="fa-regular fa-user pe-2"></i> ' . trans("Users") . '
                        </a>
                        <a class="dropdown-item statusBtn" href="javascript:void(0)"
                           data-route="' . route("admin.country.status", $item->id) . '"
                           data-bs-toggle="modal"
                           data-bs-target="#statusModal">
                            <i class="bi bi-check-circle pe-2"></i>
                           ' . trans("Status") . '
                        </a>
                       <a class="dropdown-item" href="' . $stateList . '">
                          <i class="fas fa-city dropdown-item-icon"></i> ' . trans("Manage State") . '
                        </a>
                       <a class="dropdown-item deleteBtn " href="javascript:void(0)"
                           data-route="' . $deleteurl . '"
                           data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="bi bi-trash pe-2"></i>
                           ' . trans("  Delete") . '
                        </a>
                      </div>
                    </div>
                  </div>';
            })
            ->rawColumns(['checkbox','short_name','user', 'state','property', 'status', 'action','image'])
            ->make(true);
    }

    public function countryAdd(){
        return view('admin.countries.add');
    }

    public function countryStore(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:countries,name|max:255',
            'status' => 'required|boolean',
            'iso2' => 'required|size:2',
            'iso3' => 'required|size:3',
            'phone_code' => 'required|numeric|min:1',
            'region' => 'required|string|max:100',
            'subregion' => 'required|string|max:100',
            'image' => 'nullable|image|max:2048',
            'thumb' => 'nullable|image|max:1024',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        try {
            if ($request->hasFile('image')) {
                $processImage = $this->fileUpload($request->image, config('filelocation.country.path'), null, null, 'webp', 60,);
                $image = $processImage['path'];
                $image_driver = $processImage['driver'];
            }
            if ($request->hasFile('thumb')) {
                $processThumb = $this->fileUpload($request->thumb, config('filelocation.country.path'), null, null, 'webp', 60,);
                $thumb = $processThumb['path'];
                $thumb_driver = $processThumb['driver'];
            }

            $country = new Country();

            $country->iso2 = strtoupper($request->iso2);
            $country->name = $request->name;
            $country->status = $request->status;
            $country->image = $image ?? null;
            $country->image_driver = $image_driver ?? null;
            $country->thumb = $thumb ?? null;
            $country->thumb_driver = $thumb_driver ?? null;
            $country->phone_code = $request->phone_code;
            $country->iso3 = strtoupper($request->iso3);
            $country->region = $request->region;
            $country->subregion = $request->subregion;
            $country->save();

            return back()->with('success','Country Added Successfully.');
        }catch (\Exception $e){
            return back()->with('error', $e->getMessage());
        }
    }

    public function countryEdit($id){
        try {
            $data['country'] = Country::with('state','city')->where('id',$id)->firstOr(function () {
                throw new \Exception('This Country is not available now');
            });

            return view('admin.countries.edit',$data);
        }catch (\Exception $e){
            return back()->with('error', $e->getMessage());
        }
    }

    public function countryUpdate (Request $request, $id){

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:countries,name,' . $id,
            'status' => 'required',
            'iso2' => 'required',
            'iso3' => 'required',
            'phone_code' => 'required',
            'region' => 'required',
            'subregion' => 'required',
            'image' => 'nullable',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $country = Country::where('id',$id)->firstOr(function () {
                throw new \Exception('This Country is not available now');
            });

            if ($request->hasFile('image')) {
                $processImage = $this->fileUpload($request->image, config('filelocation.country.path'), null, null, 'webp', 60,);
                $image = $processImage['path'];
                $image_driver = $processImage['driver'];

                $country->image = $image;
                $country->image_driver = $image_driver;
                $country->save();
            }
            if ($request->hasFile('thumb')) {
                $processThumb = $this->fileUpload($request->thumb, config('filelocation.country.path'), null, null, 'webp', 60,);
                $thumb = $processThumb['path'];
                $thumb_driver = $processThumb['driver'];

                $country->thumb = $thumb;
                $country->thumb_driver = $thumb_driver;
                $country->save();
            }

            $country->name = $request->name;
            $country->iso2 = strtoupper($request->iso2);
            $country->iso3 = strtoupper($request->iso3);
            $country->status = $request->status;
            $country->phone_code = $request->phone_code;
            $country->region = $request->region;
            $country->subregion = $request->subregion;
            $country->save();

            return back()->with('success','Country Updated Successfully.');
        }catch (\Exception $e){
            return back()->with('error', $e->getMessage());
        }

    }
    public function deleteMultiple(Request $request)
    {
        if (!$request->has('strIds') || empty($request->strIds)) {
            session()->flash('error', 'You did not select any data.');
            return response()->json(['error' => 1]);
        }

        $ids = is_array($request->strIds) ? $request->strIds : explode(',', $request->strIds);

        $relatedStateExist = State::whereIn('country_id', $ids)->exists();
        $relatedCityExist = City::whereIn('country_id', $ids)->exists();

        if ($relatedStateExist || $relatedCityExist) {
            session()->flash('error', 'One or more selected countries have related states or cities and cannot be deleted.');
            return response()->json(['error' => 1]);
        }

        DB::transaction(function () use ($ids) {
            $countries = Country::whereIn('id', $ids)->get();

            foreach ($countries as $country) {
                $this->fileDelete($country->image_driver, $country->image);
                $this->fileDelete($country->thumb_driver, $country->thumb);
            }

            Country::whereIn('id', $ids)->delete();
        });

        session()->flash('success', 'Selected countries deleted successfully.');
        return response()->json(['success' => 1]);
    }
    public function countryDelete($id)
    {
        try {
            $country = Country::with(['state', 'city'])->where('id', $id)->firstOr(function () {
                throw new \Exception('This country is not available now');
            });

            if ($country->state->isNotEmpty() || $country->city->isNotEmpty()) {
                return back()->with('error', 'Selected country has related states or cities and cannot be deleted.');
            }

            $this->fileDelete($country->image_driver, $country->image);

            $country->delete();

            return back()->with('success', 'Country deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
    public function status($id){
        try {
            $country = Country::select('id', 'status')
                ->where('id', $id)
                ->firstOr(function () {
                    throw new \Exception('Country not found.');
                });

            $country->status = $country->status == 1 ? 0 : 1;
            $country->save();

            return back()->with('success','Country Status Changed Successfully.');
        }catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
    public function inactiveMultiple(Request $request)
    {
        if (!$request->has('strIds') || empty($request->strIds)) {
            session()->flash('error', 'You did not select any data.');
            return response()->json(['error' => 1]);
        }

        Country::select(['id', 'status'])->whereIn('id', $request->strIds)->get()->each(function ($country) {
            $country->status = ($country->status == 0) ? 1 : 0;
            $country->save();
        });

        session()->flash('success', 'Countries status changed successfully');

        return response()->json(['success' => 1]);
    }


}
