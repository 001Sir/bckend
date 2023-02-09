<?php
namespace App\Repository\API\V1;

use App\Http\Requests\WebPageRequest;
use App\Http\Resources\WebPageCollectionResource;
use App\Http\Resources\WebPageFullResource;
use App\Http\Resources\WebPageResource;
use Illuminate\Http\JsonResponse;

use App\Interfaces\API\V1\WebPageRepositoryInterface;
use App\Models\AppSetting;
use App\Models\WebPage;
use App\Utils\Response;

class WebPageRepository implements WebPageRepositoryInterface
{
    use Response;

    /**
     * Retrieve web pages for index
     * 
     * @return JSONResponse
     */
    public function index()
    {
        try {
            $webPages = WebPage::paginate(5);

            return WebPageResource::collection($webPages);
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("There was a problem with fetching the data, try again."), "exception" => $th], 500);
        }
    }

    /** 
     * Retrieve full detailed web pages for website
    */
    public function allPosts()
    {
        try {
            $webPages = WebPage::all();
            
            return new WebPageCollectionResource(WebPageFullResource::collection($webPages));
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("There was a problem with fetching the data, try again."), "exception" => $th], 500);
        }
    }

    /**
     * Save a new web page
     * 
     * @param mixed $request
     * @return JSONResponse
     */
    public function store(WebPageRequest $request)
    {
        try {
            $webpage = WebPage::create($request->only(['title', 'slug', 'body', 'is_published', 'published_date', 'status']));

            // Send Web hook when upsert is performed
            $webHookUrl = AppSetting::where('config_name', 'website_webhook_url')->first();
            $wh = curl_init($webHookUrl->value);
            curl_setopt($wh, CURLOPT_POST, 1);
            curl_exec($wh);
            
            return new WebPageResource($webpage);
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("There was a problem with saving your web page, please check and try again."), "exception" => $th], 500);
        }
    }

    /**
     * Show a job post
     * 
     * @param $id
     * @return JSONResponse
     */
    public function show($id)
    {
        try {
            $webPage = WebPage::find($id);

            return $this->responseData($webPage);
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("There was a problem with fetching the data, try again."), "exception" => $th], 500);
        }
    }

    /**
     * Update an existing web page
     * 
     * @param mixed $request
     * @param mixed $webPage
     * @return JSONResponse
     */
    public function update(WebPageRequest $request, WebPage $webPage)
    {
        try {
            $webPage->update($request->only(['title', 'body', 'is_published', 'published_date', 'status']));

            // Send Web hook when upsert is performed
            $webHookUrl = AppSetting::where('config_name', 'website_webhook_url')->first();
            $wh = curl_init($webHookUrl->value);
            curl_setopt($wh, CURLOPT_POST, 1);
            curl_exec($wh);

            return new WebPageResource($webPage);
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("There was a problem with updating your web page, please try again."), "exception" => $th], 500);
        }
    }

    /**
     * Delete web page from database
     * 
     * @param mixed $webPage
     * @return JSONResponse
     */
    public function destroy(WebPage $webPage)
    {
        try {
            $webPage->delete();

            // Send Web hook when web page is deleted
            $webHookUrl = AppSetting::where('config_name', 'website_webhook_url')->first();
            $wh = curl_init($webHookUrl->value);
            curl_setopt($wh, CURLOPT_POST, 1);
            curl_exec($wh);

            return $this->responseData(null, __('Web Page deleted successfully.'));
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("Web Page deletion failed, try again."), "exception" => $th], 500);
        }
    }
}
