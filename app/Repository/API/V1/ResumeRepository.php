<?php

namespace App\Repository\API\V1;

use App\Http\Requests\ResumeRequest;
use App\Http\Resources\ResumeResource;

use App\Interfaces\API\V1\ResumeRepositoryInterface;
use App\Repository\API\V1\FileUploadRepository;
use App\Models\Resume;
use App\Models\User;
use App\Utils\Response;

class ResumeRepository implements ResumeRepositoryInterface
{
    use Response;

    private FileUploadRepository $fileUploadRepository;

    public function __construct(FileUploadRepository $fileUploadRepository)
    {
        $this->fileUploadRepository = $fileUploadRepository;
    }

    /**
     * Save a new resume
     * @param ResumeRequest $request
     * @return JSONResponse
     */
    public function store(ResumeRequest $request)
    {
        try {
            $resume = Resume::create($request->only(['user_id', 'file_name', 'doc_type', 'download_url', 'is_shared']));

            return new ResumeResource($resume);
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("There was a problem with saving your resume, please check and try again."), "exception" => $th], 500);
        }
    }

    /**
     * Delete resume from database
     * @param Resume $resume
     * @return JSONResponse
     */
    public function destroy(Resume $resume)
    {
        try {
            $this->fileUploadRepository->delete_doc($resume->file_name);
            $resume->delete();

            return $this->responseData(null, __('Resume deleted successfully.'));
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("Resume deletion failed, try again."), "exception" => $th], 500);
        }
    }

    /**
     * Retrieve list of resumes for user
     * 
     * @param int $id
     * @return JSONResponse
     */
    public function userResumes($id)
    {
        try {
            $user = User::where('id', $id)->with('resumes')->first();

            return $this->responseData($user['resumes']);
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("There was a problem with fetching the data, try again."), "exception" => strval($th)], 500);
        }
    }
}
