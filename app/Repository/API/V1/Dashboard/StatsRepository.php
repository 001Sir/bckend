<?php
namespace App\Repository\API\V1\Dashboard;

use App\Interfaces\API\V1\Dashboard\StatsRepositoryInterface;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\Partner;
use Illuminate\Http\JsonResponse;

use App\Utils\Response;
use Carbon\Carbon;

class StatsRepository implements StatsRepositoryInterface
{
    use Response;

    /**
     * Displays dashboard insights stats
     * 
     * @return JSONResponse
     */
    public function dashboardInsightsStats()
    {
        try {
            $jobInsights = $this->jobInsights();
            $applicantInsights = $this->applicantInsights();
            $partnerInsights = $this->partnerInsights();

            return ['jobs' => $jobInsights, 'applicants' => $applicantInsights, 'partner' => $partnerInsights];            
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("Couldn't retrieve dashboard insights, try again."), "exception" => $th], 500);
        }
    }

    /**
     * Displays Dashboard Conversion Metrics
     * 
     * @return JSONResponse
     */
    public function dashboardConversionMetrics()
    {
        try {
            $allApplicantsCount = JobApplication::all()->count();
            $shortlistedApplicantsCount = JobApplication::where('is_shortlisted', true)->count();
            $shortlistPercentageDiff = round(($shortlistedApplicantsCount / $allApplicantsCount) * 100);
            $applicantsPercentageValue = 100 - $shortlistPercentageDiff;

            return $this->responseData(['applicants' => $applicantsPercentageValue, 'shortlisted' => $shortlistPercentageDiff]);
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("Couldn't retrieve conversion metrics, try again."), "exception" => $th], 500);
        }
    }

    /**
     * Helper function to return job insights
     * 
     * @return mixed $jobInsights;
     */
    public function jobInsights()
    {
        try {
            $totalJobCount = Job::all()->count();
            $postsLastWeek = Job::whereBetween('created_at', [Carbon::now()->startOfWeek()->subWeek(), Carbon::now()->endOfWeek()->subWeek()])->count();
            $postsThisWeek = Job::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
            if($postsLastWeek > 0 && $postsThisWeek > 0) {
                $percentageDiff = (1 - $postsLastWeek / $postsThisWeek) * 100;
            } else {
                $percentageDiff = 0;
            }

            $jobInsights = [
                "total" => $totalJobCount,
                "this_week" => $postsThisWeek,
                "change_diff" => $percentageDiff
            ];

            return $jobInsights;
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("Couldn't retrieve dashboard insights, try again."), "exception" => $th], 500);
        }
    }

    /**
     * Helper function to return job application insights
     * 
     * @return mixed $jobInsights;
     */
    public function applicantInsights()
    {
        try {
            $totalApplicantCount = JobApplication::all()->count();
            $shortlistsLastMonth = JobApplication::where("is_shortlisted", true)->whereBetween('created_at', [Carbon::now()->startOfMonth()->subMonth(), Carbon::now()->endOfMonth()->subMonth()])->count();
            $shortlistsThisMonth = JobApplication::where("is_shortlisted", true)->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->count();
            if($shortlistsLastMonth > 0 && $shortlistsThisMonth > 0) {
                $percentageDiff = (1 - $shortlistsLastMonth / $shortlistsThisMonth) * 100;
            } else {
                $percentageDiff = 0;
            }

            $applicantInsights = [
                "total" => $totalApplicantCount,
                "selected_candidates" => $shortlistsThisMonth,
                "change_diff" => $percentageDiff
            ];

            return $applicantInsights;
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("Couldn't retrieve dashboard insights, try again."), "exception" => $th], 500);
        }
    }

    /**
     * Helper function to return partner insights
     * 
     * @return mixed $jobInsights;
     */
    public function partnerInsights()
    {
        try {
            $totalPartnerCount = Partner::all()->count();
            $partnersLastMonth = Partner::whereBetween('created_at', [Carbon::now()->startOfMonth()->subMonth(), Carbon::now()->endOfMonth()->subMonth()])->count();
            $partnersMonth = Partner::whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->count();
            if($partnersLastMonth > 0 && $partnersMonth > 0) {
                $percentageDiff = (1 - $partnersLastMonth / $partnersMonth) * 100;
            } else {
                $percentageDiff = 0;
            }

            $applicantInsights = [
                "total" => $totalPartnerCount,
                "new_partners" => $partnersMonth,
                "change_diff" => $percentageDiff
            ];

            return $applicantInsights;
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("Couldn't retrieve dashboard insights, try again."), "exception" => $th], 500);
        }
    }
}