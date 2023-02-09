<?php

namespace App\Repository\API\V1;

use App\Http\Requests\JobRequest;
use App\Http\Resources\JobApplicationResource;
use App\Http\Resources\JobResource;
use App\Http\Resources\Job\JobDetailedResource;
use Illuminate\Http\JsonResponse;

use App\Interfaces\API\V1\JobRepositoryInterface;
use App\Models\Category;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\JobSaved;
use App\Models\Package;
use App\Models\Partner;
use App\Models\PostDuration;
use App\Models\User;
use App\Utils\Response;
use Carbon\Carbon;

class JobRepository implements JobRepositoryInterface
{
    use Response;

    /**
     * Retrieve all jobs, with search and sorting optional parameters
     * 
     * @return JSONResponse
     */
    public function index()
    {
        try {
            $searchQuery = request()->input('s');
            $sort = request()->input('sort');
            $jobs = Job::with('partner')
                ->when($searchQuery, function ($query, $searchQuery) { // Only runs if 's' parameter is set
                    $query->where('job_title', 'Like', '%' . $searchQuery . '%');
                })
                ->when($sort, function ($query, $sort) {
                    // Sort by $sort text, i.e. if sort is 'name' sort by Ascending order, if '-name' sort by Descending order
                    $attribute = $sort;
                    $sort_order = 'ASC';
                    if (strncmp($sort, '-', 1) === 0) {
                        $sort_order = 'DESC';
                        $attribute = substr($sort, 1);
                    }
                    $query->orderBy($attribute, $sort_order);
                })
                ->paginate(10);

            return JobResource::collection($jobs);
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("There was a problem with fetching the data, try again."), "exception" => $th], 500);
        }
    }

    /**
     * Retrieve all job posts for user app homepage
     * 
     * @return JSONResponse
     */
    public function homePagePosts()
    {
        try {
            $jobs = Job::select(['id', 'partner_id', 'job_title', 'city', 'country', 'no_pay_range', 'min_salary_range', 'max_salary_range', 'is_remote', 'job_type_id', 'expiration_date', 'is_published'])->with(['partner', 'jobtype'])->paginate(10);

            return $this->responseData($jobs);
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("There was a problem with fetching the data, try again.".$th), "exception" => $th], 500);
        }
    }

    /**
     * Retrieve all job posts
     * 
     * @return JSONResponse
     */
    public function allPosts()
    {
        try {
            $jobs = Job::select(['id', 'partner_id', 'job_title', 'city', 'country', 'no_pay_range', 'min_salary_range', 'max_salary_range', 'is_remote', 'job_type_id', 'expiration_date', 'is_published'])->with(['partner', 'jobtype'])->get();

            return $this->responseData($jobs);
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("There was a problem with fetching the data, try again.".$th), "exception" => $th], 500);
        }
    }

    /**
     * Save a new job
     * 
     * @param mixed $request
     * @return JSONResponse
     */
    public function store(JobRequest $request)
    {
        try {
            $premiumPostDuration = PostDuration::where('id', $request->job_active_duration)->where('is_paid', true)->exists();
            $postDurationItem = PostDuration::where('id', $request->job_active_duration)->first();
            $postDuration = Carbon::now()->addMinutes($postDurationItem->duration);
            
            if($premiumPostDuration) {
                $package = Partner::find($request->partner_id)->with('package')->get()->pluck('package');
                $packageLongPostDuration = Package::find($package[0][0]->id)->whereHas('features', function ($query) {
                    return $query->where('config_name', 'long-post-duration');
                })->exists();
                if($packageLongPostDuration) {
                    $request['expiration_date'] = $postDuration;
                    $job = Job::create($request->only([
                        'partner_id',
                        'job_title',
                        'is_remote',
                        'category_id',
                        'city',
                        'country',
                        'no_pay_range',
                        'min_salary_range',
                        'max_salary_range',
                        'job_type_id',
                        'desc',
                        'job_active_duration',
                        'is_published',
                        'expiration_date',
                        'status'
                    ]));

                    if($request->skills) {
                        $skills = explode(',', $request->skills);
                        $job->skills()->sync($skills);
                    }
                    
                    return new JobResource($job);
                } else {
                    return $this->responseError(__("Your subscribed package is insufficient to use this job active duration for the job"), 500);
                }
            } else {
                $request['expiration_date'] = $postDuration;
                $job = Job::create($request->only([
                    'partner_id',
                    'job_title',
                    'is_remote',
                    'category_id',
                    'city',
                    'country',
                    'no_pay_range',
                    'min_salary_range',
                    'max_salary_range',
                    'job_type_id',
                    'desc',
                    'job_active_duration',
                    'is_published',
                    'expiration_date',
                    'status'
                ]));
                
                if($request->skills) {
                    $skills = explode(',', $request->skills);
                    $job->skills()->sync($skills);
                }

                return new JobResource($job);
            }
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("There was a problem with saving your job, please check and try again."), "exception" => strval($th)], 500);
        }
    }

    /**
     * Show a job post
     * 
     * @param $id
     * @return JSONResponse
     */
    public function show($id)
    {
        try {
            $job = Job::where('id', $id)->with('applicants')->first();

            return new JobDetailedResource($job);
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => "There was a problem with retrieving your job post, please try again.", "exception" => $th], 500);
        }
    }

    /**
     * Retrieve job as a user
     * 
     * @param mixed $id
     * @param User $user
     * @return JSONResponse
     */
    public function retrieveJobUser($id, User $user)
    {
        try {
            $job = Job::find($id);
            $jobApplicationExists = JobApplication::where('job_id', $job->id)->where('user_id', $user->id)->exists();
            $jobApplication = JobApplication::where('job_id', $job->id)->where('user_id', $user->id)->first();
            $jobSaved = JobSaved::where('job_id', $job->id)->where('user_id', $user->id)->exists();
            
            if ($jobApplicationExists) {
                $shortListed = ($jobApplication->is_shortlisted) ? true : false;
                return $this->responseData(['has_applied' => true, 'shortlisted' => $shortListed, 'saved' => $jobSaved, 'job' => new JobResource($job), 'application' => new JobApplicationResource($jobApplication)]);
            }

            return $this->responseData(['has_applied' => false, 'shortlisted' => false, 'saved' => $jobSaved, 'job' => new JobResource($job), 'application' => '']);
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => "There was a problem with retrieving your job post, please try again.", "exception" => $th], 500);
        }
    }

    /**
     * Retrieve jobs with applicants information
     * 
     * @return JSONResponse
     */
    public function retrieveJobApplicants($id)
    {
        try {
            $job = Job::with('applicants')->find($id);

            return new JobDetailedResource($job);
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => "There was a problem with retrieving your job post, please try again.", "exception" => $th], 500);
        }
    }

    /**
     * Update an existing job
     * 
     * @param mixed $request
     * @param mixed $job
     * @return JSONResponse
     */
    public function update(JobRequest $request, Job $job)
    {
        try {
            $premiumPostDuration = PostDuration::where('id', $request->job_active_duration)->where('is_paid', true)->exists();

            if ($premiumPostDuration) {
                $package = Partner::find($request->partner_id)->with('package')->get()->pluck('package');
                $packageLongPostDuration = Package::find($package[0][0]->id)->whereHas('features', function ($query) {
                    return $query->where('config_name', 'long-post-duration');
                })->exists();
                if ($packageLongPostDuration) {
                    $job->update($request->only([
                        'job_title',
                        'is_remote',
                        'category_id',
                        'city',
                        'country',
                        'no_pay_range',
                        'min_salary_range',
                        'max_salary_range',
                        'job_type_id',
                        'desc',
                        'is_published',
                        'status'
                    ]));

                    if($request->skills) {
                        $skills = explode(',', $request->skills);
                        $job->skills()->sync($skills);
                    }

                    return new JobResource($job);
                } else {
                    return $this->responseError(__("Your subscribed package is insufficient to use this post duration for the job"), 500);
                }
            } else {
                $job->update($request->only([
                    'job_title',
                    'is_remote',
                    'category_id',
                    'city',
                    'country',
                    'no_pay_range',
                    'min_salary_range',
                    'max_salary_range',
                    'job_type_id',
                    'desc',
                    'is_published',
                    'status'
                ]));

                if($request->skills) {
                    $skills = explode(',', $request->skills);
                    $job->skills()->sync($skills);
                }

                return new JobResource($job);
            }
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("There was a problem with updating your job, please try again."), "exception" => $th], 500);
        }
    }

    /**
     * Delete job from database
     * 
     * @param mixed $job
     * @return JSONResponse
     */
    public function destroy(Job $job)
    {
        try {
            $job->delete();

            return $this->responseData(null, __('Job deleted successfully.'));
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("Job deletion failed, try again."), "exception" => $th], 500);
        }
    }

    /**
     * Retrieve list of jobs by partner
     * @param Partner $partner
     * @return JSONResponse
     */
    public function partnerJobs(Partner $partner)
    {
        try {
            $jobs = Job::where('partner_id', $partner->id)->with(['partner', 'jobtype'])->withCount('applicants')->get();

            return $this->responseData($jobs);
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("There was problem with retrieving jobs, please try again."), "exception" => strval($th)], 500);
        }
    }

    /**
     * Retrieve list of jobs by partner
     * @param Partner $partner
     * @return JSONResponse
     */
    public function categoryJobs(Category $category)
    {
        try {
            $jobs = Job::where('category_id', $category->id)->with(['partner', 'jobtype'])->get();

            return $this->responseData($jobs);
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("There was problem with retrieving jobs, please try again."), "exception" => $th], 500);
        }
    }

    /**
     * Job Application Stats
     * @return JSONResponse
     */
    public function jobStats(): JsonResponse
    {
        try {
            $selected_applicants = array();
            $job_applicants = array();
            for ($i = 1; $i <= 12; $i++) {
                $selected_applicants[$i] = JobApplication::whereMonth('applied_time', date('m', strtotime('-' . $i . ' month')))->where('is_shortlisted', true)->count();
                $job_applicants[$i] = JobApplication::whereMonth('applied_time', date('m', strtotime('-' . $i . ' month')))->count();
            }

            return $this->responseData(['job_applications' => $job_applicants, 'selected_applicants' => $selected_applicants]);
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("We're having trouble retrieving job stats."), "exception" => $th], 500);
        }
    }

    /**
     * Toggle Job Status
     * 
     * @param Job $job
     * @param String $status
     * @return JSONResponse
     */
    public function toggleJobStatus(Job $job, String $status): JsonResponse
    {
        try {
            switch($status) {
                case 'active':
                    $job->is_published = true;
                    $job->status = 'active';
                    break;
                case 'paused':
                    $job->is_published = false;
                    $job->status = 'paused';
                    break;
            }

            $job->save();

            return $this->responseData("Job was updated");
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("We couldn't update the job post"), "exception" => strval($th)], 500);
        }
    }
}
