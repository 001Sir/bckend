<?php
namespace App\Interfaces\API\V1;

use App\Http\Requests\BlogPostRequest;
use App\Models\BlogPost;

interface BlogPostRepositoryInterface
{
    public function index();
    public function store(BlogPostRequest $request);
    public function update(BlogPostRequest $request, BlogPost $blogPost);
    public function destroy(BlogPost $blogPost);
    public function featuredArticles();
}