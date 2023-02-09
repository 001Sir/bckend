<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UserRequest;

class AuthController extends Controller
{
	public function loginPartner(Request $request) {
		$email = $request->input('email');
		$password = $request->input('password');

		if(!Auth::guard()->attempt(['email' => $email, 'password' => $password])) {
			return new JsonResponse(['result' => 'Login info incorrect!']);
		}

		$res = User::where('email', $email)->first();
		$res->token = $res->createToken("WEB TOKEN")->plainTextToken;

		$res = new UserResource($res);
		$res->isReturningToken = true;

		return $res;
	}

	public function loginWorker(Request $request) {
		$phoneNumber = $request->input('phoneNumber');
		$res = User::where('phoneNumber', $phoneNumber)->first();

		if ($res != null) {
			$res->token = $res->createToken("WEB TOKEN")->plainTextToken;
			$res = new UserResource($res);
			$res->isReturningToken = true;

			return $res;
		}
		else
			return new JsonResponse(['result' => 'Not registered!']);
	}

	public function registerPartner(UserRequest $request) {
		$request['password'] = Hash::make($request->password);

		$user = User::create($request->all());
		$user->token = $user->createToken("WEB TOKEN")->plainTextToken;

		$user = new UserResource($user);
		$user->isReturningToken = true;

		return $user;
	}

	public function registerWorker(UserRequest $request) {
		$user = User::create($request->all());
		$user->token = $user->createToken("WEB TOKEN")->plainTextToken;

		$user = new UserResource($user);
		$user->isReturningToken = true;

		return $user;
	}
}