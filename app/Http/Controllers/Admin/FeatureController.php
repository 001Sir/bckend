<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\FeatureRequest;
use App\Models\Feature;
use App\Repository\API\V1\FeatureRepository;

class FeatureController extends Controller
{
    private FeatureRepository $featureRepository;

    public function __construct(FeatureRepository $featureRepository)
    {
        $this->featureRepository = $featureRepository;
    }

    public function index()
    {
        return $this->featureRepository->index();
    }

    public function store(FeatureRequest $request)
    {
        return $this->featureRepository->store($request);
    }

    public function update(FeatureRequest $request, $id)
    {
        $feature = Feature::find($id);
        return $this->featureRepository->update($request, $feature);
    }

    public function destroy(Feature $feature)
    {
        return $this->featureRepository->destroy($feature);
    }
}
