<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(new JsonResponse(['result' => ['Your email or phone number already exists']]));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        if ($this->request->get('isWorker') == 1) {
            return [
                'email' => ['email', 'unique:users', 'required'],
                'firstName' => ['string', 'required'],
                'lastName' => ['string', 'required'],
                'phoneNumber' => ['string', 'unique:users', 'required'],
            ];
        }
        else {
            return [
                'email' => ['email', 'unique:users', 'required'],
                'password' => ['string', 'required'],
                'firstName' => ['string', 'required'],
                'lastName' => ['string', 'required'],
                'phoneNumber' => ['string', 'unique:users', 'required'],
                'companyName' => ['string', 'required'],
                'companyLocation' => ['string', 'required'],
                'EIN' => ['string', 'required']
            ];
        }
    }
}
