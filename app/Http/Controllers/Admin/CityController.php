<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use App\Models\CountryCities;
use App\Models\CountryStates;
use App\Models\State;
use App\Traits\Notify;
use App\Traits\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class CityController extends Controller
{
    use Upload, Notify;
    public function citylist($country, $state)
    {
        try {
            $currentMonth = now()->startOfMonth();
            $nextMonth = now()->copy()->addMonth()->startOfMonth();
            $currentYear = now()->startOfYear();
            $nextYear = now()->copy()->addYear()->startOfYear();

            $allData = City::selectRaw(
                'COUNT(*) as allCities,
             SUM(status = 1) as allActiveCities,
             SUM(status = 0) as allInactiveCities,
             SUM(CASE WHEN created_at >= ? AND created_at < ? THEN 1 ELSE 0 END) as allCitiesThisMonth,
             SUM(CASE WHEN created_at >= ? AND created_at < ? THEN 1 ELSE 0 END) as allCitiesThisYear',
                [$currentMonth, $nextMonth, $currentYear, $nextYear]
            )
                ->where('country_id', $country)
                ->where('state_id', $state)
                ->first();

            $totalCities = $allData->allCities ?? 0;

            $data['allCities'] = $totalCities;
            $data['allActiveCities'] = $allData->allActiveCities ?? 0;
            $data['allInactiveCities'] = $allData->allInactiveCities ?? 0;
            $data['allCitiesThisMonth'] = $allData->allCitiesThisMonth ?? 0;
            $data['allCitiesThisYear'] = $allData->allCitiesThisYear ?? 0;

            if ($totalCities > 0) {
                $data['activeCityPercentage'] = ($data['allActiveCities'] / $totalCities) * 100;
                $data['inactiveCityPercentage'] = ($data['allInactiveCities'] / $totalCities) * 100;
                $data['cityThisMonthPercentage'] = ($data['allCitiesThisMonth'] / $totalCities) * 100;
                $data['cityThisYearPercentage'] = ($data['allCitiesThisYear'] / $totalCities) * 100;
            } else {
                $data['activeCityPercentage'] = 0;
                $data['inactiveCityPercentage'] = 0;
                $data['cityThisMonthPercentage'] = 0;
                $data['cityThisYearPercentage'] = 0;
            }

            return view('admin.countries.citylist', $data, compact('country', 'state'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function countryStateCityList(Request $request,$country,$state)
    {
        $city = City::query()->with('state:id,name')->withCount(['user','packages'])->where('country_id',$country)->where('state_id',$state);
        if (!empty($request->search['value'])) {
            $city = $city->where('name', 'LIKE', '%' . $request->search['value'] . '%');
        }

        return DataTables::of($city)
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
            ->addColumn('name', function ($item) {
                return '<a class="d-flex align-items-center me-2" href="#">
                            <div class="flex-grow-1 ms-3">
                              <span class="fs-6 text-body">' . optional($item)->name . '</span>
                            </div>
                        </a>';
            })
            ->addColumn('code', function ($item) {
                return '<a class="d-flex align-items-center me-2" href="#">
                            <div class="flex-grow-1 ms-3">
                              <span class="fs-6 text-body">' . optional($item)->country_code . '</span>
                            </div>
                        </a>';
            })
            ->addColumn('state', function ($item) {
                return '<a class="d-flex align-items-center me-2" href="#">
                            <div class="flex-grow-1 ms-3">
                              <span class="fs-6 text-body">' . optional($item->state)->name . '</span>
                            </div>
                        </a>';
            })
            ->addColumn('property', function ($item) {
                return ' <span class="badge bg-soft-secondary text-dark">' . $item->packages_count . '</span>';
            })
            ->addColumn('user', function ($item) {
                return ' <span class="badge bg-soft-info text-dark">' . $item->user_count . '</span>';
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

                $editUrl = route('admin.country.state.city.edit', [$item->country_id,$item->state_id, $item->id ]);
                $deleteurl = route('admin.country.state.city.delete', [$item->country_id, $item->state_id, $item->id ]);
                $status = route('admin.country.state.city.status', [$item->id ]);
                return '<div class="btn-group" role="group">
                      <a href="' . $editUrl . '" class="btn btn-white btn-sm edit_user_btn">
                        <i class="bi-pencil-fill me-1"></i> ' . trans("Edit") . '
                      </a>
                    <div class="btn-group">
                      <button type="button" class="btn btn-white btn-icon btn-sm dropdown-toggle dropdown-toggle-empty" id="userEditDropdown" data-bs-toggle="dropdown" aria-expanded="false"></button>
                      <div class="dropdown-menu dropdown-menu-end mt-1" aria-labelledby="userEditDropdown">
                        <a class="dropdown-item" href="' . route("admin.users", ['city' => $item->name]) . '">
                           <i class="fa-regular fa-user pe-2"></i> ' . trans("Users") . '
                        </a>
                        <a class="dropdown-item statusBtn" href="javascript:void(0)"
                           data-route="' . $status . '"
                           data-bs-toggle="modal"
                           data-bs-target="#statusModal">
                            <i class="bi bi-check-circle pe-2"></i>
                           ' . trans("Status") . '
                        </a>
                       <a class="dropdown-item text-danger" href="' . $deleteurl . '">
                          <i class="bi-trash dropdown-item-icon text-danger"></i> ' . trans("Delete") . '
                        </a>
                      </div>
                    </div>
                  </div>';
            })
            ->rawColumns(['checkbox','name','code', 'status','state','property','user', 'action'])
            ->make(true);
    }

    public function countryStateCityEdit($country,$state,$city){
        try {
            $data['city'] = City::where('id',$city)->where('country_id', $country)->where('state_id', $state)->firstOr(function () {
                throw new \Exception('City not found');
            });

            return view('admin.countries.cityedit',$data);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }


    }

    public function countryStateCityUpdate(Request $request,$country,$state,$city){
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'min:5',
                'max:255',
                Rule::unique('cities')->ignore($city),
            ],
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }


        try {
            $city = City::where('country_id',$country)->where('state_id',$state)->where('id',$city)->firstOr(function () {
                throw new \Exception('This City is not available now');
            });

            $city->update([
                'name'=>$request->name,
                'status'=>$request->status,
            ]);

            return back()->with('success','City Updated.');

        }catch (\Exception $e){
            return back()->with('error', $e->getMessage());
        }
    }

    public function deleteMultipleStateCity(Request $request)
    {
        if ($request->strIds == null) {
            session()->flash('error', 'You do not select any Country.');
            return response()->json(['error' => 1]);
        } else {
            DB::transaction(function () use ($request) {
                City::whereIn('id', $request->strIds)->delete();
            });

            session()->flash('success', 'Selected Data deleted successfully');
            return response()->json(['success' => 1]);
        }
    }
    public function countryStateCityDelete($country,$state,$city){

        try {
            $city = City::where('country_id',$country)->where('state_id',$state)->where('id',$city)->firstOr(function () {
                throw new \Exception('This Country is not available now');
            });

            $city->delete();

            return back()->with('success','City Deleted Successfully.');

        }catch (\Exception $e){
            return back()->with('error', $e->getMessage());
        }

    }


    public function countryStateAddCity($country,$state){
        try {
            $data['country'] = Country::select('id','name')->where('id',$country)->firstOr(function () {
                throw new \Exception('Country not found.');
            });
            $data['state'] = State::select('id', 'name','country_code')->where('id',$state)->firstOr(function () {
                throw new \Exception('State not found.');
            });

            return view('admin.countries.cityAdd',$data);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function countryStateStoreCity(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', 'regex:/^[\pL\s]+$/u'],
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        try {
            $city = new City();

            $city->country_id = $request->country_id;
            $city->state_id = $request->state_id;
            $city->country_code = $request->country_code;
            $city->name = $request->name;
            $city->status = $request->status;
            $city->save();

            throw_if(!$city, 'Something went wrong while storing city data. Please try again later.');

            return back()->with('success','City Added Successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
    public function status($id){
        try {
            $city = City::select('id', 'status')
                ->where('id', $id)
                ->firstOr(function () {
                    throw new \Exception('City not found.');
                });

            $city->status = ($city->status == 1) ? 0 : 1;
            $city->save();

            return back()->with('success','City Status Changed Successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
    public function inactiveMultiple(Request $request)
    {
        if (!$request->has('strIds') || empty($request->strIds)) {
            session()->flash('error', 'You did not select any data.');
            return response()->json(['error' => 1]);
        }

        City::select(['id', 'status'])->whereIn('id', $request->strIds)->get()->each(function ($city) {
            $city->status = ($city->status == 0) ? 1 : 0;
            $city->save();
        });

        session()->flash('success', 'Cities status changed successfully');

        return response()->json(['success' => 1]);
    }
}
