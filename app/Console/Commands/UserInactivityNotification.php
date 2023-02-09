<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\UserActivityLog;
use App\Models\UserNotificationSetting;
use App\Repository\API\V1\Notifications\NotificationMessagingRepository;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UserInactivityNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inactivity:user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends a notification to user if inactive for more than two days.';

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
        $inactiveUsers = UserActivityLog::where('activity_name', 'app-open')->where('updated_at', '<=', Carbon::now()->subDays(2)->toDateTimeString())->get();

        foreach ($inactiveUsers as $inactiveuser) {
            $user = User::find($inactiveuser->user_id);
            $notificationSent = UserActivityLog::where('user_id', $user->id)->where('activity_name', 'inactive-notification-sent')->exists();

            if(!$notificationSent) {
                $notificationSetting = UserNotificationSetting::where('user_id', $user->id)->first();
                if ($notificationSetting) {
                    if ($notificationSetting->inactivity_notification) {
                        $this->notificationMessagingRepository->sendNotificationToUser([
                            'title' => __('More job opportunities await you!'),
                            'body' => __('We can help you to get better job opportunities.'),
                        ], $user);
                        // Update as user activity to prevent repetitive notifications
                        UserActivityLog::create(
                            [
                                'user_id' => $user->id,
                                'activity_name' => 'inactive-notification-sent',
                                'activity_value' => Carbon::now(),
                            ]
                        );
                    }
                }
            }
        }

        Log::info(__("Inactive users (last 48 hours) were notified"));
    }
}
