<?php
namespace App\Repository\API\V1;

use App\Http\Requests\SkillRequest;
use App\Http\Resources\SkillResource;
use Illuminate\Http\JsonResponse;

use App\Interfaces\API\V1\SkillRepositoryInterface;
use App\Models\Skill;
use App\Models\User;
use App\Utils\Response;

class SkillRepository implements SkillRepositoryInterface
{
    use Response;

    /**
     * Retrieve all skills
     * @return JSONResponse
     */
    public function index()
    {
        try {
            $skills = Skill::paginate(10);

            return SkillResource::collection($skills);
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("There was a problem with fetching the data, try again."), "exception" => $th], 500);
        }
    }

    /**
     * Save a new skill
     * @param mixed $request
     * @return JSONResponse
     */
    public function store(SkillRequest $request)
    {
        try {
            $skills = Skill::create($request->only(['name', 'is_hinted']));

            return new SkillResource($skills);
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("There was a problem with saving your skill, please check and try again."), "exception" => $th], 500);
        }
    }

    /**
     * Update an existing skill
     * @param mixed $request
     * @param mixed $skill
     * @return JSONResponse
     */
    public function update(SkillRequest $request, Skill $skill)
    {
        try {
            $skill->update($request->only(['name', 'is_hinted']));

            return new SkillResource($skill);
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("There was a problem with updating your skill, please try again."), "exception" => $th], 500);
        }
    }

    /**
     * Sync skills to user
     * 
     * @param User $user
     * @param mixed $skills
     * @return JSONResponse
     */
    public function addSkillsToUser(User $user, $skills)
    {
        try {
            if($user->skills()) {
                $skills = explode(',', $skills);
                $user->skills()->sync($skills);
            }

            return $this->responseData($user);
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("There was a problem with saving your skill, please check and try again."), "exception" => strval($th)], 500);
        }
    }

    /**
     * Delete skill from database
     * @param mixed $skill
     * @return JSONResponse
     */
    public function destroy(Skill $skill)
    {
        try {
            $skill->delete();

            return $this->responseData(null, __('Skill deleted successfully.'));
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("Skill deletion failed, try again."), "exception" => $th], 500);
        }
    }


    /**
     * Retreive list of skills that are hintable
     * @return JSONResponse
     */
    public function hintableSkills()
    {
        try {
            $skills = Skill::where('is_hinted', true)->get();

            return SkillResource::collection($skills);
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("Couldn't retrieve list of skills"), "exception" => $th], 500);
        }
    }

    /**
     * Retrieve list of skills on user
     * 
     * @param int $id
     * @return JSONResponse
     */
    public function userSkills($id)
    {
        try {
            $user = User::where('id', $id)->with('skills')->first();

            return $this->responseData($user['skills']);
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("Couldn't retrieve list of skills"), "exception" => strval($th)], 500);
        }
    }
}
