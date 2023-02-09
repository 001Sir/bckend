<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SkillRequest;
use App\Models\Skill;
use App\Repository\API\V1\SkillRepository;

class SkillController extends Controller
{
    private SkillRepository $skillRepository;

    public function __construct(SkillRepository $skillRepository)
    {
        $this->skillRepository = $skillRepository;
    }

    public function index()
    {
        return $this->skillRepository->index();
    }

    public function store(SkillRequest $request)
    {
        return $this->skillRepository->store($request);
    }

    public function update(SkillRequest $request, $id)
    {        
        $skill = Skill::find($id);
        return $this->skillRepository->update($request, $skill);
    }

    public function destroy(Skill $skill)
    {
        return $this->skillRepository->destroy($skill);
    }
}
