<?php
namespace App\Interfaces\API\V1;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;

interface CategoryRepositoryInterface
{
    public function index();
    public function store(CategoryRequest $request);
    public function update(CategoryRequest $request, Category $category);
    public function destroy(Category $category);
    public function getJobsInCategory(Category $category);
}