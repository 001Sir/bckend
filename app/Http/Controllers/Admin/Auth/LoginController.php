<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Repository\Authentication\LoginRepository;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * @purpose
 *
 * Provides a login controller for authenticating the admin user on the dashboard
 */
class LoginController extends Controller
{
    private LoginRepository $loginRepository;

    public function __construct(LoginRepository $loginRepository)
    {
        $this->loginRepository = $loginRepository;
    }

    public function login(Request $request): JsonResponse
    {
        return $this->loginRepository->login($request);
    }  

    public function details(Request $request): JsonResponse
    {
        return $this->loginRepository->details($request);
    } 

    public function logout(Request $request): JsonResponse
    {
        return $this->loginRepository->logout($request);
    }
}
