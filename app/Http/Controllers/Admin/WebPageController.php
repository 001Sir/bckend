<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\WebPageRequest;
use App\Models\WebPage;
use App\Repository\API\V1\WebPageRepository;
use Illuminate\Http\Request;

class WebPageController extends Controller
{
    private WebPageRepository $webPageRepository;

    public function __construct(WebPageRepository $webPageRepository)
    {
        $this->webPageRepository = $webPageRepository;
    }

    public function index()
    {
        return $this->webPageRepository->index();
    }

    public function allPosts()
    {
        return $this->webPageRepository->allPosts();
    }

    public function show($id)
    {
        return $this->webPageRepository->show($id);
    }
    
    public function update(WebPageRequest $request, $id)
    {        
        $webPage = WebPage::find($id);
        return $this->webPageRepository->update($request, $webPage);
    }
    
    public function destroy(Request $request)
    {
        $webPage = WebPage::find($request->id);
        return $this->webPageRepository->destroy($webPage);

    }
}
