<?php
namespace App\Interfaces\API\V1;

use App\Http\Requests\AppSettingRequest;
use App\Models\AppSetting;

interface AppSettingRepositoryInterface
{
    public function generalSettings();
    public function applicationSettings();
    public function store(AppSettingRequest $request);
    public function destroy(AppSetting $appSetting);
}