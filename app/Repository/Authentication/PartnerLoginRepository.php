<?php
namespace App\Repository\Authentication;

use App\Http\Resources\PartnerResource;
use App\Models\Partner;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;


use App\Repository\Authentication\LoginRepository;
use App\Utils\Response;
use Illuminate\Support\Facades\DB;

class PartnerLoginRepository extends LoginRepository
{
    use Response;

    /**
     * Partner Login method
     * @param Request $request
     * @return JSONResponse
     */
    public function login(Request $request)
    {
        try {
            $validatedUser = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required',
                'device_name' => 'string'
            ]);

            if($validatedUser->fails()){
                return $this->responseError(__("Invalid input, please check."), 401);
            }
            
            $unverified = DB::table('password_resets')
            ->where('email', $request->email)->first();
            
            if($unverified) {
                return $this->responseError(__("Your account is unverified, please try again."), 401);
            }

            if(!Auth::guard('partners')->attempt(['email' => $request->email, 'password' => $request->password])) {
                return $this->responseError(__("Invalid credentials, please try again."), 401);
            }

            $partner = Partner::where('email', $request->email)->first();

            $token_name = ($request->device_name) ? $request->device_name : "API TOKEN";

            return $this->responseData([
                'status' => true,
                'token' => $partner->createToken($token_name)->plainTextToken
            ], 'Partner Login Successful.');
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("There was a problem with logging you in, try again."), "exception" => $th], 500);
        }
    }

    /**
     * Partner Details method
     * @return JSONResponse
     */
    public function details(Request $request)
    {
        $user = $request->user();

        return $this->responseData(new PartnerResource($user));
    }

    /**
     * Partner Logout method
     * @return JSONResponse
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        
        return $this->responseData([
            'message' => __('Partner logged out succesfully.')
        ]);
    }
}