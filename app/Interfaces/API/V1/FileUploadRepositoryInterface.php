<?php
namespace App\Interfaces\API\V1;

use App\Http\Requests\DocUploadRequest;
use App\Http\Requests\ImageUploadRequest;


interface FileUploadRepositoryInterface
{
    public function upload_image(ImageUploadRequest $request);
    public function upload_doc(DocUploadRequest $request);
}