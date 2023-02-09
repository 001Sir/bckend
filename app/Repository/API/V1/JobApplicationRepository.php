<?php

namespace App\Repository\API\V1;

use App\Http\Requests\JobApplicationRequest;
use App\Http\Resources\JobApplicationResource;
use Illuminate\Http\JsonResponse;

use App\Interfaces\API\V1\JobApplicationRepositoryInterface;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\Partner;
use App\Models\PartnerNotificationSetting;
use App\Models\User;
use App\Models\UserNotificationSetting;
use App\Repository\API\V1\Notifications\NotificationMessagingRepository;
use App\Utils\Response;

class JobApplicationRepository implements JobApplicationRepositoryInterface
{
    use Response;

    private NotificationMessagingRepository $notificationMessagingRepository;

    public function __construct(NotificationMessagingRepository $notificationMessagingRepository)
    {
        $this->notificationMessagingRepository = $notificationMessagingRepository;
    }

    /**
     * Retrieve all job applications
     * 
     * @return JSONResponse
     */
    public function index(Job $job)
    {
        try {
            $jobApplicants = JobApplication::with(['job','user'])->where('job_id', $job->id)->paginate(10);

            return JobApplicationResource::collection($jobApplicants);
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("There was a problem with fetching the data, try again."), "exception" => $th], 500);
        }
    }

    /**
     * Save a new job application
     * 
     * @param JobApplicationRequest $request
     * @return JSONResponse
     */
    public function store(JobApplicationRequest $request)
    {
        try {
            $job = Job::find($request->job_id)->first();
            $partner = Partner::find($job->partner_id)->first();
            $applicationExists = JobApplication::where('job_id', $request->job_id)->where('user_id', $request->user_id)->count();

            if($applicationExists) {
                return $this->responseError(__("You've already applied for this position"), 409);
            }
            $jobApplication = JobApplication::create($request->only([
                'job_id',
                'user_id',
                'resume_id',
                'applied_time',
                'is_shortlisted',
                'selected_time',
                'is_read',
                'read_receipt_time'
            ]));
            
            $notificationSetting = PartnerNotificationSetting::where('partner_id', $job->partner_id)->first();
            if($notificationSetting) {
                if($notificationSetting->new_applicants) {
                    $result = $this->notificationMessagingRepository->sendNotificationToPartner([
                        'title' => __('A new job applicant'),
                        'body' => __('New Applicant Notification Text', ['job_title', $job->job_title]),
                    ],$partner);
                }
            }

            return new JobApplicationResource($jobApplication);
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("There was a problem with saving your job application, please check and try again."), "exception" => $th], 500);
        }
    }

    /**
     * Delete job application from database
     * 
     * @param JobApplication $jobApplication
     * @return JSONResponse
     */
    public function destroy(JobApplication $jobApplication)
    {
        try {
            $jobApplication->delete();

            return $this->responseData(null, __("Job Application deleted successfully."));
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("Job Application deletion failed, try again."), "exception" => $th], 500);
        }
    }

    /**
     * Mark user as shortlisted
     * 
     * @param JobApplication $jobApplication
     * @param User $user
     * @return JSONResponse
     */
    public function shortlistApplicant(JobApplication $jobApplication)
    {
        try {
            $user = User::find($jobApplication->user_id)->first();
            $job = Job::with('partner')->where('id', $jobApplication->job_id)->first();
            $notificationSetting = UserNotificationSetting::where('user_id', $user->id)->first();

            if($jobApplication->is_shortlisted == true) {
                return $this->responseError(__("Applicant already shortlisted."), 500);
            }

            if($notificationSetting->shortlisted_notification) {
                $this->notificationMessagingRepository->sendNotificationToUser([
                    'title' => __("You've been shortlisted... Hurray!!!"),
                    'body' => __("Applicant Shortlisted Notification Text", ['job_title' => $job->job_title, 'company' => $job->partner->company_name]),
                ],$user);
            }
            
            $jobApplication->update(['is_shortlisted' => true]);

            return new JobApplicationResource($jobApplication);
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("We couldn't shortlist the job application, try again."), "exception" => $th], 500);
        }
    }

    /**
     * Return list of job applications by user
     * 
     * @param User $user
     * @return JSONResponse
     */
    public function listJobApplicationUser(User $user)
    {
        try {
            $listJobApplicationsUser = JobApplication::where('user_id', $user->id)->get();

            return JobApplicationResource::collection($listJobApplicationsUser);
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("Job Application retrieval failed, try again."), "exception" => $th], 500);
        }
    }

    /**
     * Return list of shortlisted job applications by user
     * 
     * @param User $user
     * @return JSONResponse
     */
    public function listShortlistedJobsUser(User $user)
    {
        try {
            $listShortlistedJobsUser = JobApplication::where('user_id', $user->id)->where('is_shortlisted', true)->get();

            return JobApplicationResource::collection($listShortlistedJobsUser);
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("Job Application retrieval failed, try again."), "exception" => $th], 500);
        }
    }

    /**
     * Toggle Shortlist Read / Unread status
     * 
     * @param Jobapplication $jobApplication
     * @return JSONResponse
     */
    public function toggleReadStatus(JobApplication $jobApplication)
    {
        try {
            $currentStatus = $jobApplication->is_read;
            $jobApplication->update(['is_read' => !$currentStatus]);

            return new JobApplicationResource($jobApplication);
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("There was a problem with fetching the data, try again."), "exception" => strval($th)], 500);
        }
    }

    /**
     * Return count of user unread shortlisted applications
     * 
     * @param User $user
     * @return JSONResponse
     */
    public function countUserShortlistedJobs(User $user)
    {
        try {
            $unreadUserShortlistedApplications = JobApplication::where('user_id', $user->id)->where('is_shortlisted', true)->where('is_read', false)->count();

            return $this->responseData($unreadUserShortlistedApplications);
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("Job Application Shortlisted count retrieval failed, try again."), "exception" => $th], 500);
        }
    }
}
