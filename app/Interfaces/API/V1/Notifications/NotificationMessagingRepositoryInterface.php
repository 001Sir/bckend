<?php
namespace App\Interfaces\API\V1\Notifications;

use App\Models\Partner;
use App\Models\User;

interface NotificationMessagingRepositoryInterface
{
    public function sendNotificationToUser($message, User $user);
    public function sendNotificationToPartner($message, Partner $partner);
}