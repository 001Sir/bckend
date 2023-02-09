<?php

namespace App\Repository\API\V1\Notifications;

use App\Http\Requests\Notifications\PartnerNotificationSettingRequest;
use App\Http\Requests\Notifications\UserNotificationSettingRequest;
use App\Http\Resources\Notifications\PartnerNotificationSettingResource;
use App\Http\Resources\Notifications\UserNotificationSettingResource;
use App\Interfaces\API\V1\Notifications\NotificationSettingRepositoryInterface;
use App\Models\Partner;
use App\Models\PartnerNotificationSetting;
use App\Models\User;
use App\Models\UserNotificationSetting;
use App\Utils\Response;

class NotificationSettingRepository implements NotificationSettingRepositoryInterface
{
    use Response;

    /**
     * Retrieve settings for user
     * 
     * @param User $user
     * @return JSONResponse
     */
    public function retrieveSettingsUser(User $user)
    {
        try {
            $setting = UserNotificationSetting::where('user_id', $user->id)->first();

            if ($setting != null) {
                return new UserNotificationSettingResource($setting);
            }

            return $this->responseError(__("There were no settings"), 500);
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("There was a problem with fetching the data, try again."), "exception" => $th], 500);
        }
    }

    /**
     * Retrieve settings for partner
     * 
     * @param Partner $partner
     * @return JSONResponse
     */
    public function retrieveSettingsPartner(Partner $partner)
    {
        try {
            $setting = PartnerNotificationSetting::where('partner_id', $partner->id)->first();

            if ($setting != null) {
                return new PartnerNotificationSettingResource($setting);
            }

            return $this->responseError(__("There were no settings"), 500);
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("There was a problem with fetching the data, try again."), "exception" => $th], 500);
        }
    }

    /**
     * Create or update notification setting
     * 
     * @param UserNotificationSettingRequest $request
     * @return JSONResponse
     */
    public function saveSettingUser(UserNotificationSettingRequest $request)
    {
        try {
            $setting = UserNotificationSetting::updateOrCreate(
                ['user_id' => $request->user_id],
                [
                    'shortlisted_notification' => $request->shortlisted_notification,
                    'inactivity_notification' => $request->inactivity_notification
                ],
            );


            return new UserNotificationSettingResource($setting);
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("There was a problem with saving the setting, try again."), "exception" => $th], 500);
        }
    }

    /**
     * Create or update notification setting
     * 
     * @param PartnerNotificationSettingRequest $request
     * @return JSONResponse
     */
    public function saveSettingPartner(PartnerNotificationSettingRequest $request)
    {
        try {
            $setting = PartnerNotificationSetting::updateOrCreate(
                ['partner_id' => $request->partner_id],
                [
                    'new_applicants' => $request->new_applicants,
                    'job_expiry' => $request->job_expiry
                ]
            );

            return new PartnerNotificationSettingResource($setting);
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("There was a problem with saving the setting, try again."), "exception" => $th], 500);
        }
    }
}
