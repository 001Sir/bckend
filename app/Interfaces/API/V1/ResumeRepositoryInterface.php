<?php
namespace App\Interfaces\API\V1;

use App\Http\Requests\ResumeRequest;
use App\Models\Resume;

interface ResumeRepositoryInterface
{
    public function store(ResumeRequest $request);
    public function destroy(Resume $resume);
}