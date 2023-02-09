<?php
namespace App\Interfaces\API\V1;

use App\Http\Requests\WebPageRequest;
use App\Models\WebPage;

interface WebPageRepositoryInterface
{
    public function index();
    public function allPosts();
    public function store(WebPageRequest $request);
    public function show($id);
    public function update(WebPageRequest $request, WebPage $webPage);
    public function destroy(WebPage $webPage);
}