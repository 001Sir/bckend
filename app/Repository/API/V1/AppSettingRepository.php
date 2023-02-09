<?php
namespace App\Repository\API\V1;

use App\Http\Requests\AppSettingRequest;
use App\Http\Resources\AppSettingResource;
use Illuminate\Http\JsonResponse;

use App\Interfaces\API\V1\AppSettingRepositoryInterface;
use App\Models\AppSetting;
use App\Utils\Response;

class AppSettingRepository implements AppSettingRepositoryInterface
{
    use Response;

    /**
     * Retrieve all features from general settings
     * 
     * @return JSONResponse
     */
    public function generalSettings()
    {
        try {
            $appsettings = AppSetting::where('setting_tab', 'general')->get();

            return AppSettingResource::collection($appsettings);
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("There was a problem with fetching the data, try again."), "exception" => $th], 500);
        }
    }

    /**
     * Retrieve all settings from application settings
     * 
     * @return JSONResponse
     */
    public function applicationSettings()
    {
        try {
            $appsettings = AppSetting::where('setting_tab', 'application')->get();

            return AppSettingResource::collection($appsettings);
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("There was a problem with fetching the data, try again."), "exception" => $th], 500);
        }
    }

    /**
     * Save a new setting (individual)
     * 
     * @param AppSettingRequest $request
     * @return JSONResponse
     */
    public function store(AppSettingRequest $request)
    {
        try {
            $feature = AppSetting::updateOrCreate(
                [
                    'config_name' => $request->config_name
                ],
                $request->only(['config_name', 'display_name', 'value', 'setting_tab'])
            );

            // Send Web hook when upsert is performed
            $webHookUrl = AppSetting::where('config_name', 'website_webhook_url')->first();
            $wh = curl_init($webHookUrl->value);
            curl_setopt($wh, CURLOPT_POST, 1);
            curl_exec($wh);

            return new AppSettingResource($feature);
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("There was a problem with saving your app setting, please check and try again."), "exception" => $th], 500);
        }
    }

    /**
     * Delete feature from database
     * 
     * @param AppSetting $appSetting
     * @return JSONResponse
     */
    public function destroy(AppSetting $appSetting)
    {
        try {
            $appSetting->delete();

            return $this->responseData(null, __('App Setting deleted successfully.'));
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("App Setting deletion failed, try again."), "exception" => $th], 500);
        }
    }
}
