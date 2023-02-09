<?php
namespace App\Repository\API\V1;

use App\Http\Requests\DocUploadRequest;
use App\Http\Requests\ImageUploadRequest;
use App\Interfaces\API\V1\FileUploadRepositoryInterface;
use App\Utils\Response;
use Illuminate\Support\Facades\File;

class FileUploadRepository implements FileUploadRepositoryInterface
{
    use Response;

    /**
     * Upload an image file and return fileName
     * @return String
     */
    public function upload_image(ImageUploadRequest $request)
    {
        $path = public_path('img/uploads');

        $file = $request->file('image');

        $fileName = time() . '-' . uniqid() . '_' . trim($file->getClientOriginalName());
        
        $request->image->move($path,$fileName);

        return $this->responseData(['file_name' => $fileName], ['success' => __('Image has been uploaded.')]);
    }

    /**
     * Upload a document and return path, file_type
     * @return array(file_name, file_type)
     */
    public function upload_doc(DocUploadRequest $request)
    {
        $path = public_path('docs/uploads');

        $file = $request->file('doc');

        $fileName = time() . '-' . uniqid() . '_' . trim($file->getClientOriginalName());

        $ext = $file->getClientOriginalExtension();
        
        $request->doc->move($path,$fileName);

        return $this->responseData(['file_name' => $fileName, 'ext' => $ext], ['Success' => __('Document has been uploaded.')]);
    }

    /**
     * Delete a document
     * @param String $file_name
     * @return boolean
     */
    public function delete_doc($file_name)
    {
        $path = public_path('docs/uploads');

        $file = $path . '/' . $file_name;

        if(File::exists($file)) {
            File::delete($file);
        }

        return true;
    }
}