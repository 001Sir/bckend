<?php
namespace App\Interfaces\API\V1\Notifications;

use App\Http\Requests\Notifications\PartnerNotificationDeviceRequest;
use App\Http\Requests\Notifications\UserNotificationDeviceRequest;
use App\Models\Partner;
use App\Models\User;

interface NotificationDeviceRepositoryInterface
{
    public function retrieveUserDevices(User $user);
    public function retrievePartnerDevices(Partner $partner);
    public function saveUserDevice(UserNotificationDeviceRequest $request);
    public function savePartnerDevice(PartnerNotificationDeviceRequest $request);
}