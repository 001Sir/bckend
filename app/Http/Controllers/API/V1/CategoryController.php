<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Category;
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
    
    public function getJobsInCategory($id)
    {
        $category = Category::find($id)->first();
        return $this->categoryRepository->getJobsInCategory($category);
    }
}
