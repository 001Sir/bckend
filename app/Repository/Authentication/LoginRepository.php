<?php
namespace App\Repository\Authentication;

use App\Http\Resources\AdminResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

use App\Interfaces\Authentication\LoginRepositoryInterface;
use App\Models\Admin;
use App\Utils\Response;

class LoginRepository implements LoginRepositoryInterface
{
    use Response;

    /**
     * Admin Login method
     * @param Request $request
     * @return JSONResponse
     */
    public function login(Request $request)
    {
        try {
            $validatedUser = Validator::make($request->only(['email', 'password', 'device_name']), [
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if($validatedUser->fails()){
                return $this->responseError(__("Invalid input, please check."), 401);
            }

            if(!Auth::guard('admins')->attempt(['email' => $request->email, 'password' => $request->password])) {
                return $this->responseError(__("Invalid credentials, please try again."), 401);
            }

            $admin = Admin::where('email', $request->email)->first();

            return $this->responseData([
                'status' => true,
                'token' => $admin->createToken("WEB TOKEN")->plainTextToken
            ], 'Login Successful.');
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("There was a problem with logging you in, try again."), "exception" => $th], 500);
        }
    }

    /**
     * Admin Details method
     * @return JSONResponse
     */
    public function details(Request $request)
    {
        $user = $request->user();

        return $this->responseData([
            'data' => new AdminResource($user),
        ]);
    }

    /**
     * Admin Logout method
     * @return JSONResponse
     */
    public function logout(Request $request)
    {
        if($request->user()->currentAccessToken()) {
            $request->user()->currentAccessToken()->delete();
        } else {
            $request->user()->tokens()->delete();
        }
        
        return $this->responseData([
            'message' => __('Admin logged out succesfully.')
        ]);
    }
}
