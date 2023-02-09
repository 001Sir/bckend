<?php

namespace App\Repository\API\V1\Notifications;

use App\Http\Requests\Notifications\PartnerNotificationDeviceRequest;
use App\Http\Requests\Notifications\UserNotificationDeviceRequest;
use App\Interfaces\API\V1\Notifications\NotificationDeviceRepositoryInterface;
use App\Models\Partner;
use App\Models\PartnerNotificationDevice;
use App\Models\User;
use App\Models\UserNotificationDevice;
use App\Utils\Response;

class NotificationDeviceRepository implements NotificationDeviceRepositoryInterface
{
    use Response;

    /**
     * Retrieve user device keys
     * 
     * @param User $user
     * @return JSONResponse
     */
    public function retrieveUserDevices(User $user)
    {
        try {
            $devices = UserNotificationDevice::where('user_id', $user->id)->pluck('device_key');

            return $this->responseData($devices);
        } catch (\Throwable $th) {
            return $this->responseError(["msg"=> __("There was a problem with fetching the data, try again."), "exception" => $th], 500);
        }
    }
    
    /**
     * Retrieve partner device keys
     * 
     * @param Partner $partner
     * @return JSONResponse
     */
    public function retrievePartnerDevices(Partner $partner)
    {
        try {
            $devices = PartnerNotificationDevice::where('partner_id', $partner->id)->pluck('device_key');
        
            return $this->responseData($devices);
        } catch (\Throwable $th) {
            return $this->responseError(["msg"=> __("There was a problem with fetching the data, try again."), "exception" => $th], 500);
        }
    }
    
    /**
     * Save user device
     * 
     * @param UserNotificationDeviceRequest $request
     * @return JSONResponse
     */
    public function saveUserDevice(UserNotificationDeviceRequest $request)
    {
        try {
            $device = UserNotificationDevice::create($request->all());

            return $this->responseData($device);
        } catch (\Throwable $th) {
            return $this->responseError(["msg"=> __("There was a problem with saving the device information, try again."), "exception" => $th], 500);
        }
    }
    
    /**
     * Save partner device
     * 
     * @param PartnerNotificationDeviceRequest $request
     * @return JSONResponse
     */
    public function savePartnerDevice(PartnerNotificationDeviceRequest $request)
    {
        try {
            $device = PartnerNotificationDevice::create($request->all());
        
            return $this->responseData($device);
        } catch (\Throwable $th) {
            return $this->responseError(["msg"=> __("There was a problem with saving the device information, try again."), "exception" => $th], 500);
        }
    }
}
