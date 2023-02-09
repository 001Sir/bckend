<?php
namespace App\Interfaces\API\V1\Notifications;

use App\Http\Requests\Notifications\PartnerNotificationSettingRequest;
use App\Http\Requests\Notifications\UserNotificationSettingRequest;
use App\Models\Partner;
use App\Models\User;

interface NotificationSettingRepositoryInterface
{
    public function retrieveSettingsUser(User $user);
    public function retrieveSettingsPartner(Partner $partner);
    public function saveSettingUser(UserNotificationSettingRequest $request);
    public function saveSettingPartner(PartnerNotificationSettingRequest $request);
}