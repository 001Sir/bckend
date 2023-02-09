<?php
namespace App\Interfaces\API\V1\ActivityLog;

use App\Models\User;

interface UserActivityLogRepositoryInterface
{
    public function saveAppOpeningActivity(User $user);
}