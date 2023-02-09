<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Repository\User\UserRepository;

/**
 * @purpose
 *
 * Provides a controller for performing actions on users
 */
class UserController extends Controller
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index()
    {
        return $this->userRepository->index();
    }

    public function store(UserRequest $request)
    {
        return $this->userRepository->store($request);
    }

    public function update(UserRequest $request, $id)
    {        
        $user = User::find($id);
        return $this->userRepository->update($request, $user);
    }

    public function destroy(User $user)
    {        
        return $this->userRepository->destroy($user);
    }
}
