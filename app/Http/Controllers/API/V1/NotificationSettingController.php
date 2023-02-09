<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Notifications\PartnerNotificationSettingRequest;
use App\Http\Requests\Notifications\UserNotificationSettingRequest;
use App\Models\Partner;
use App\Models\User;
use App\Repository\API\V1\Notifications\NotificationSettingRepository;
use Illuminate\Http\Request;

class NotificationSettingController extends Controller
{
    private NotificationSettingRepository $notificationRepository;

    public function __construct(NotificationSettingRepository $notificationRepository)
    {
        $this->notificationRepository = $notificationRepository;
    }

    public function retrieveUser(Request $request)
    {
        $user = User::find($request->user_id);
        return $this->notificationRepository->retrieveSettingsUser($user);
    }

    public function retrievePartner(Request $request)
    {
        $partner = Partner::find($request->partner_id);
        return $this->notificationRepository->retrieveSettingsPartner($partner);
    }

    public function saveSettingUser(UserNotificationSettingRequest $request)
    {
        return $this->notificationRepository->saveSettingUser($request);
    }

    public function saveSettingPartner(PartnerNotificationSettingRequest $request)
    {
        return $this->notificationRepository->saveSettingPartner($request);
    }
}
