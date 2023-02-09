<?php
namespace App\Repository\API\V1;

use App\Http\Requests\BlogPostRequest;
use App\Http\Resources\BlogPostCollectionResource;
use App\Http\Resources\BlogPostCompleteResource;
use App\Http\Resources\BlogPostResource;
use Illuminate\Http\JsonResponse;

use App\Interfaces\API\V1\BlogPostRepositoryInterface;
use App\Models\AppSetting;
use App\Models\BlogPost;
use App\Utils\Response;

class BlogPostRepository implements BlogPostRepositoryInterface
{
    use Response;

    /**
     * Retrieve all blog post
     * 
     * @return JSONResponse
     */
    public function index()
    {
        try {
            $blogPosts = BlogPost::paginate(10);

            return BlogPostResource::collection($blogPosts);
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("There was a problem with fetching the data, try again."), "exception" => $th], 500);
        }
    }

    /**
     * Retrieve all blog posts for the website
     * 
     * @return JSONResponse
     */
    public function web_index()
    {
        try {
            $blogPosts = BlogPost::all();

            return new BlogPostCollectionResource(BlogPostCompleteResource::collection($blogPosts));
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("There was a problem with fetching the data, try again."), "exception" => $th], 500);
        }
    }

    /**
     * Save a new blog post
     * 
     * @param mixed $request
     * @return JSONResponse
     */
    public function store(BlogPostRequest $request)
    {
        try {
            $blogPost = BlogPost::create($request->only(['post_title', 'slug', 'featured_image', 'author_id', 'is_featured', 'is_published', 'excerpt', 'body']));

            // Send Web hook when upsert is performed
            $webHookUrl = AppSetting::where('config_name', 'website_webhook_url')->first();
            $wh = curl_init($webHookUrl->value);
            curl_setopt($wh, CURLOPT_POST, 1);
            curl_exec($wh);

            return new BlogPostResource($blogPost);
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("There was a problem with saving your blog post, please check and try again."), "exception" => $th], 500);
        }
    }

    /**
     * Retrieve a Blog Post
     * 
     * @param $id
     * @return JSONResponse
     */
    public function show($id)
    {
        try {
            $blogPost = BlogPost::find($id);

            return $this->responseData($blogPost);
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("There was a problem with fetching the data, try again."), "exception" => $th], 500);
        }
    }

    /**
     * Update an existing blog post
     * 
     * @param BlogPostRequest $request
     * @param BlogPost $blogPost
     * @return JSONResponse
     */
    public function update(BlogPostRequest $request, BlogPost $blogPost)
    {
        try {
            $blogPost->update($request->only(['post_title', 'slug', 'featured_image', 'author_id', 'is_featured', 'is_published', 'excerpt', 'body']));

            // Send Web hook when upsert is performed
            $webHookUrl = AppSetting::where('config_name', 'website_webhook_url')->first();
            $wh = curl_init($webHookUrl->value);
            curl_setopt($wh, CURLOPT_POST, 1);
            curl_exec($wh);

            return new BlogPostResource($blogPost);
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("There was a problem with updating your blog post, please try again."), "exception" => $th], 500);
        }
    }

    /**
     * Publish and Unpublish post
     * 
     * @param BlogPost $blogPost
     * @return JSONResponse
     */
    public function togglePostPublish(BlogPost $blogPost)
    {
        try {
            $blogPost->update(['is_published' => !$blogPost->is_published]);

            // Send Web hook when upsert is performed
            $webHookUrl = AppSetting::where('config_name', 'website_webhook_url')->first();
            $wh = curl_init($webHookUrl->value);
            curl_setopt($wh, CURLOPT_POST, 1);
            curl_exec($wh);

            return $this->responseData($blogPost);
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("There was a problem with updating your blog post, please try again."), "exception" => $th], 500);
        }
    }

    /**
     * Delete blog post from database
     * 
     * @param mixed $blogPost
     * @return JSONResponse
     */
    public function destroy(BlogPost $blogPost)
    {
        try {
            $blogPost->delete();

            return $this->responseData(null, __("Blog Post deleted successfully."));
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("Blog Post deletion failed, try again."), "exception" => $th], 500);
        }
    }

    /**
     * Retreive list of featured articles
     * 
     * @return JSONResponse
     */
    public function featuredArticles()
    {
        try {
            $blogPosts = BlogPost::where('is_featured', true)->get();

            return BlogPostCompleteResource::collection($blogPosts);
            return true;
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("There was a problem with fetching the data, try again."), "exception" => $th], 500);
        }
    }
}
