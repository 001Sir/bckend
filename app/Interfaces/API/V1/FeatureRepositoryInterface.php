<?php
namespace App\Interfaces\API\V1;

use App\Http\Requests\FeatureRequest;
use App\Models\Feature;

interface FeatureRepositoryInterface
{
    public function index();
    public function store(FeatureRequest $request);
    public function update(FeatureRequest $request, Feature $feature);
    public function destroy(Feature $feature);
}