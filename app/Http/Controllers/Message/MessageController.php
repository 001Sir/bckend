<?php

namespace App\Http\Controllers\Message;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;
use App\Http\Resources\MessageResource;
use Illuminate\Http\JsonResponse;

class MessageController extends Controller
{
	public function list(Request $request) {
		$messages = Message::where('partnerId', $request->partnerId)->where('workerId', $request->workerId)->get();

		return new JsonResponse(['result' => 'success', 'list' => MessageResource::collection($messages)]);
	}

	public function contact(Request $request) {
		$messages = Message::where($request->isWorker ? 'workerId' : 'partnerId', $request->id)
					->groupBy($request->isWorker ? 'partnerId' : 'workerId')
					->orderByDesc('created_at')->get();

		foreach($messages as &$message) {
			$user = User::find($message[$request->isWorker ? 'partnerId' : 'workerId']);
			$message->name = $user->firstName.' '.$user->lastName;
		}
		return new JsonResponse(['result' => 'success', 'list' => MessageResource::collection($messages)]);
	}

	public function insert(Request $request) {
		Message::create($request->only(['partnerId', 'workerId', 'msg', 'sender']));

		return new JsonResponse(['result' => 'success']);
	}
}