<?php
namespace App\Repository\API\V1;

use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\JobResource;
use Illuminate\Http\JsonResponse;

use App\Interfaces\API\V1\CategoryRepositoryInterface;
use App\Models\Category;
use App\Utils\Response;

class CategoryRepository implements CategoryRepositoryInterface
{
    use Response;

    /**
     * Retrieve all categories
     * @return JSONResponse
     */
    public function index()
    {
        try {
            $categories = Category::paginate(10);

            return CategoryResource::collection($categories);
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("There was a problem with fetching the data, try again."), "exception" => $th], 500);
        }
    }

    /**
     * Save a new category
     * @param mixed $request
     * @return JSONResponse
     */
    public function store(CategoryRequest $request)
    {
        try {
            $categories = Category::create($request->all());

            return new CategoryResource($categories);
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("There was a problem with saving your category, please check and try again."), "exception" => $th], 500);
        }
    }

    /**
     * Update an existing category
     * @param mixed $request
     * @param mixed $category
     * @return JSONResponse
     */
    public function update(CategoryRequest $request, Category $category)
    {
        try {
            $category->update($request->all());

            return new CategoryResource($category);
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("There was a problem with updating your category, please try again."), "exception" => $th], 500);
        }
    }

    /**
     * Delete category from database
     * @param mixed $category
     * @return JSONResponse
     */
    public function destroy(Category $category)
    {
        try {
            $category->delete();

            return $this->responseData(null, __('Category deleted successfully.'));
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("Category deletion failed, try again."), "exception" => $th], 500);
        }
    }

    /**
     * Delete category from database
     * @param mixed $category
     * @return JSONResponse
     */
    public function getJobsInCategory(Category $category)
    {
        try {
            $jobs = $category->jobs()->get();

            return JobResource::collection($jobs);
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("There was a problem with retrieving jobs in selected category, try again."), "exception" => $th], 500);
        }
    }
}
