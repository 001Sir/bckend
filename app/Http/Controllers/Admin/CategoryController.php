<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Http\Requests\CategoryRequest;
use App\Repository\API\V1\CategoryRepository;

class CategoryController extends Controller
{
    private CategoryRepository $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function index()
    {
        return $this->categoryRepository->index();
    }

    public function store(CategoryRequest $request)
    {
        return $this->categoryRepository->store($request);
    }

    public function update(CategoryRequest $request, Category $category)
    {
        return $this->categoryRepository->update($request, $category);
    }

    public function destroy(Category $category)
    {
        return $this->categoryRepository->destroy($category);
    }
}
