<?php

namespace App\Repository\API\V1\ActivityLog;

use App\Http\Resources\UserActivityLogResource;
use App\Interfaces\API\V1\ActivityLog\UserActivityLogRepositoryInterface;
use App\Models\User;
use App\Models\UserActivityLog;
use App\Utils\Response;

class UserActivityLogRepository implements UserActivityLogRepositoryInterface
{
    use Response;

    /**
     * Save App Opening Activity (app-open)
     */
    public function saveAppOpeningActivity(User $user)
    {
        try {
            $activityLog = UserActivityLog::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'activity_name' => 'app-open'
                ],
                [
                    'activity_value' => 'true'
                ],
            );

            $activityLog->touch(); // Updates the `updated_at` column

            return new UserActivityLogResource($activityLog);
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("There was a problem with saving the activity log, try again."), "exception" => $th], 500);
        }
    }
}
