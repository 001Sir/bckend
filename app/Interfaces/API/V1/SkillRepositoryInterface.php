<?php
namespace App\Interfaces\API\V1;

use App\Http\Requests\SkillRequest;
use App\Models\Skill;

interface SkillRepositoryInterface
{
    public function index();
    public function store(SkillRequest $request);
    public function update(SkillRequest $request, Skill $skill);
    public function destroy(Skill $skill);
    public function hintableSkills();
}