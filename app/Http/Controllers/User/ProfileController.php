<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;

class ProfileController extends Controller
{
	public function companyUpdate(Request $request) {
		$user = User::find($request->id);

		$user->update($request->only([
			'companyName', 'companyLocation', 'EIN'
		]));

		return new JsonResponse(['result' => 'success']);
	}

	public function personalUpdate(Request $request) {
		$user = User::find($request->id);
		
		$user->update($request->only([
			'firstName', 'lastName', 'birthyear', 'address'
		]));

		return new JsonResponse(['result' => 'success']);
	}
}