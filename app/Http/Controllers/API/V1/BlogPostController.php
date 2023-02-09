<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Repository\API\V1\BlogPostRepository;

class BlogPostController extends Controller
{
    private BlogPostRepository $blogPostRepository;

    public function __construct(BlogPostRepository $blogPostRepository)
    {
        $this->blogPostRepository = $blogPostRepository;
    }

    public function index()
    {
        return $this->blogPostRepository->web_index();
    }

    public function show($id)
    {
        return $this->blogPostRepository->show($id);
    }

    public function featuredArticles()
    {
        return $this->blogPostRepository->featuredArticles();
    }
}
