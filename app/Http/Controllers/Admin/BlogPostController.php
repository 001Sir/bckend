<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BlogPostRequest;
use App\Models\BlogPost;
use App\Repository\API\V1\BlogPostRepository;
use Illuminate\Http\Request;

class BlogPostController extends Controller
{
    private BlogPostRepository $blogPostRepository;

    public function __construct(BlogPostRepository $blogPostRepository)
    {
        $this->blogPostRepository = $blogPostRepository;
    }

    public function index()
    {
        return $this->blogPostRepository->index();
    }

    public function store(BlogPostRequest $request)
    {
        return $this->blogPostRepository->store($request);
    }

    public function update(BlogPostRequest $request, $id)
    {
        $blogPost = BlogPost::find($id);
        return $this->blogPostRepository->update($request, $blogPost);
    }

    public function togglePublishedStatus($id)
    {
        $blogPost = BlogPost::find($id);
        return $this->blogPostRepository->togglePostPublish($blogPost);
    }

    public function show($id)
    {
        return $this->blogPostRepository->show($id);
    }

    public function destroy(Request $request)
    {
        $blogPost = BlogPost::find($request->id);
        return $this->blogPostRepository->destroy($blogPost);
    }

    public function featuredArticles()
    {
        return $this->blogPostRepository->featuredArticles();
    }
}
