<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repository\API\V1\SkillRepository;
use Illuminate\Http\Request;

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

    public function hintableSkills()
    {
        return $this->skillRepository->hintableSkills();
    }

    public function userSkills($id)
    {
        return $this->skillRepository->userSkills($id);
    }

    public function addSkillsToUser($id, Request $request)
    {
        $user = User::find($id);
        return $this->skillRepository->addSkillsToUser($user, $request->skills);
    }
}
