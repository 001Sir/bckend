<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostDurationRequest;
use App\Models\PostDuration;
use App\Repository\API\V1\PostDurationRepository;

class PostDurationController extends Controller
{
    private PostDurationRepository $postdurationRepository;

    public function __construct(PostDurationRepository $postdurationRepository)
    {
        $this->postdurationRepository = $postdurationRepository;
    }

    public function index()
    {
        return $this->postdurationRepository->index();
    }

    public function store(PostDurationRequest $request)
    {
        return $this->postdurationRepository->store($request);
    }

    public function update(PostDurationRequest $request, $id)
    {        
        $postduration = PostDuration::find($id);
        return $this->postdurationRepository->update($request, $postduration);
    }

    public function destroy($id)
    {
        $postduration = PostDuration::find($id);
        return $this->postdurationRepository->destroy($postduration);
    }
}
