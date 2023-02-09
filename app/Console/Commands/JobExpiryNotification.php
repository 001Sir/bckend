<?php

namespace App\Console\Commands;

use App\Mail\JobExpiry;
use App\Models\Job;
use App\Models\Partner;
use App\Models\PartnerNotificationSetting;
use App\Repository\API\V1\Notifications\NotificationMessagingRepository;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class JobExpiryNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'job:expiration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notification to partner if job expires in a week';

    private NotificationMessagingRepository $notificationMessagingRepository;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->notificationMessagingRepository = new NotificationMessagingRepository();
        $jobsExpiringWeek = Job::whereDate('expiration_date', Carbon::now()->addWeek())->get();
        $jobsExpiringWeekLogged = Job::whereDate('expiration_date', Carbon::now()->addWeek())->get()->pluck('id', 'job_title')->toArray();

        foreach ($jobsExpiringWeek as $job) {
            $partner = Partner::find($job->partner_id);

            if ($job->status == 'active') {
                if ($partner->id) {
                    $notificationSetting = PartnerNotificationSetting::where('partner_id', $partner->id)->first();
                    if ($notificationSetting) {
                        if ($notificationSetting->job_expiry) {
                            Mail::to($partner->email)->send(new JobExpiry($job->job_title));
                            $this->notificationMessagingRepository->sendNotificationToPartner([
                                'title' => __('Job expires in a week'),
                                'body' => __('Job Expires Notification Text', ['job_title' => $job->job_title]),
                            ], $partner);
                        }
                    }
                }
            }
        }

        return Log::info(__("Jobs Expiring in a week notifications sent to partners"), $jobsExpiringWeekLogged);
    }
}
