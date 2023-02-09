<?php
namespace App\Interfaces\User;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;

interface UserRepositoryInterface
{
    public function index();    
    public function store(UserRequest $request);
    public function update(UserRequest $request, User $user);
    public function destroy(User $user);
    public function verifyEmail(Request $request);
    public function blockUser(User $user);
    public function unBlockUser(User $user);
}