<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Notifications\PartnerNotificationDeviceRequest;
use App\Http\Requests\Notifications\UserNotificationDeviceRequest;
use App\Models\Partner;
use App\Models\User;
use App\Repository\API\V1\Notifications\NotificationDeviceRepository;
use Illuminate\Http\Request;

class NotificationDeviceController extends Controller
{
    private NotificationDeviceRepository $notificationDeviceRepository;

    public function __construct(NotificationDeviceRepository $notificationDeviceRepository)
    {
        $this->notificationDeviceRepository = $notificationDeviceRepository;
    }

    public function retrieveUserDevices(Request $request)
    {
        $user = User::find($request->user_id);
        return $this->notificationDeviceRepository->retrieveUserDevices($user);
    }

    public function retrievePartnerDevices(Request $request)
    {
        $partner = Partner::find($request->partner_id);
        return $this->notificationDeviceRepository->retrievePartnerDevices($partner);
    }

    public function saveUserDevice(UserNotificationDeviceRequest $request)
    {
        return $this->notificationDeviceRepository->saveUserDevice($request);
    }

    public function savePartnerDevice(PartnerNotificationDeviceRequest $request)
    {
        return $this->notificationDeviceRepository->savePartnerDevice($request);
    }
}
