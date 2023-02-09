<?php
namespace App\Interfaces\API\V1;

use App\Http\Requests\PostDurationRequest;
use App\Models\PostDuration;

interface PostDurationRepositoryInterface
{
    public function index();
    public function store(PostDurationRequest $request);
    public function update(PostDurationRequest $request, PostDuration $postDuration);
    public function destroy(PostDuration $postDuration);
}