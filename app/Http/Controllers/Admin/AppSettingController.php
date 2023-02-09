<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AppSettingRequest;
use App\Models\AppSetting;
use App\Repository\API\V1\AppSettingRepository;
use Illuminate\Http\Request;

class AppSettingController extends Controller
{
    private AppSettingRepository $appSettingRepository;

    public function __construct(AppSettingRepository $appSettingRepository)
    {
        $this->appSettingRepository = $appSettingRepository;
    }
    
    public function generalSettings()
    {
        return $this->appSettingRepository->generalSettings();
    }
    
    public function applicationSettings()
    {
        return $this->appSettingRepository->applicationSettings();
    }

    public function store(AppSettingRequest $request)
    {
        return $this->appSettingRepository->store($request);
    }

    public function destroy(AppSetting $appSetting)
    {
        return $this->appSettingRepository->destroy($appSetting);
    }
}
