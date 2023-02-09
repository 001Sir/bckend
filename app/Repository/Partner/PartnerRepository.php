<?php

namespace App\Repository\Partner;

use App\Http\Requests\EditPartnerRequest;
use App\Http\Requests\PartnerRequest;
use App\Http\Resources\PartnerDashboardResource;
use App\Http\Resources\PartnerResource;
use Illuminate\Http\JsonResponse;
use App\Interfaces\Partner\PartnerRepositoryInterface;
use App\Mail\VerifyEmail;
use App\Models\Partner;
use App\Models\PartnerNotificationSetting;
use App\Utils\Response;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class PartnerRepository implements PartnerRepositoryInterface
{
    use Response;

    /**
     * Retrieve name only of all partners
     * 
     * @return JSONResponse
     */
    public function all()
    {
        try {
            $partners = Partner::all()->pluck('company_name');

            return $this->responseData($partners);
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("There was a problem with fetching the data, try again."), "exception" => $th], 500);
        }
    }

    /**
     * Retrieve all partners (companies)
     * 
     * @return JSONResponse
     */
    public function index()
    {
        try {
            $searchQuery = request()->input('s');
            $sort = request()->input('sort');
            $partners = Partner::with('package')
                ->when($searchQuery, function ($query, $searchQuery) { // Only runs if 's' parameter is set
                    $query->where('company_name', 'Like', '%' . $searchQuery . '%');
                })
                ->when($sort, function ($query, $sort) {
                    // Sort by $sort text, i.e. if sort is 'company_name' sort by Ascending order, if '-company_name' sort by Descending order
                    $attribute = $sort;
                    $sort_order = 'ASC';
                    if (strncmp($sort, '-', 1) === 0) {
                        $sort_order = 'DESC';
                        $attribute = substr($sort, 1);
                    }
                    $query->orderBy($attribute, $sort_order);
                })
                ->paginate(10);

            return PartnerResource::collection($partners);
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("There was a problem with fetching the data, try again."), "exception" => $th], 500);
        }
    }

    /**
     * Retrieve all partners basic information
     * 
     * @return JSONResponse
     */
    public function list()
    {
        try {
            $partners = Partner::all(['id', 'short_name', 'img_url', 'employee_count']);

            return $this->responseData($partners);
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("There was a problem with fetching the data, try again.".$th), "exception" => $th], 500);
        }
    }

    /**
     * Save a new partner
     * 
     * @param mixed $request
     * @return JSONResponse
     */
    public function store(PartnerRequest $request)
    {
        try {
            $request['password'] = Hash::make($request->password);
            $partner = Partner::create($request->only([
                'img_url',
                'company_name',
                'short_name',
                'email',
                'password',
                'bio',
                'employee_count'
            ]));

            if($partner->id) {
                $partner->package()->syncWithPivotValues([1], ['valid_until' => Carbon::now()->addCenturies(3)]); // Add free package (id = 1) to partner


                PartnerNotificationSetting::create([
                    'partner_id' => $partner->id,
                    'new_applicants' => true,
                    'job_expiry' => true
                ]);

                $verify =  DB::table('password_resets')->where([
                    ['email', $request->all()['email']]
                ]);

                if ($verify->exists()) {
                    $verify->delete();
                }

                $pin = rand(100000, 999999);

                DB::table('password_resets')
                    ->insert(
                        [
                            'email' => $request->all()['email'],
                            'token' => $pin,
                            'created_at' => Carbon::now()
                        ]
                    );
            }

            Mail::to($request->email)->send(new VerifyEmail($request->email, $pin));

            return new PartnerResource($partner);
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("There was a problem with saving the partner, please check and try again.".$th), "exception" => $th], 500);
        }
    }

    /**
     * Update an existing partner
     * 
     * @param mixed $request
     * @param mixed $partner
     * @return JSONResponse
     */
    public function update(EditPartnerRequest $request, Partner $partner)
    {
        try {
            $partner->update($request->only(['img_url', 'company_name', 'short_name', 'email', 'bio', 'employee_count']));

            return new PartnerResource($partner);
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("There was a problem with updating the partner, please try again."), "exception" => $th], 500);
        }
    }

    /**
     * Delete partner from database
     * 
     * @param mixed $partner
     * @return JSONResponse
     */
    public function destroy(Partner $partner)
    {
        try {
            $partner->delete();

            return $this->responseData(null, __('Partner deleted successfully.'));
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("Partner deletion failed, try again."), "exception" => $th], 500);
        }
    }

    /**
     * Verify an email
     * 
     * @param mixed $request
     * @return JSONResponse
     */
    public function verifyEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['email', 'required'],
            'token' => ['required'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with(['message' => $validator->errors()]);
        }
        $select = DB::table('password_resets')
            ->where('email', $request->email)
            ->where('token', $request->token);

        if ($select->get()->isEmpty()) {
            return new JsonResponse(['success' => false, 'message' => __("Invalid PIN")], 400);
        }

        $select = DB::table('password_resets')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->delete();

        $partner = Partner::where('email', $request->email)->first();
        $partner->email_verified_at = Carbon::now();
        $partner->save();

        return new JsonResponse(['success' => true, 'message' => __("Email is verified")], 200);
    }

    /**
     * Retrieve partner information for dashboard
     * 
     * @return JSONResponse
     */
    public function getPartnersDashboard()
    {
        try {
            $partners = Partner::withCount('jobs')->paginate(4);

            return PartnerDashboardResource::collection($partners);
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("There was a problem retrieving partners for the dashboard"), "exception" => $th], 500);
        }
    }

    /**
     * Retrieve Top Performers for dashboard
     * 
     * @param String $page_no
     * @return JSONResponse
     */
    public function getTopPerformers()
    {
        try {
            $results_per_page = 4;
            $topPerformers = Partner::join(DB::raw('(SELECT partner_id, SUM(is_published) as jobs_posted FROM jobs GROUP BY partner_id ORDER BY SUM(is_published)) as top_publisher'), function ($join) {
                $join->on('top_publisher.partner_id', '=', 'partners.id');
            })
                ->select(['partners.*', 'top_publisher.jobs_posted'])
                ->orderBy('top_publisher.jobs_posted', 'DESC')
                ->paginate($results_per_page);

            return $this->responseData($topPerformers);
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("There was a problem retrieving top performing partners."), "exception" => $th], 500);
        }
    }
}
