<?php

namespace App\Http\Controllers\Shift;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Shift;
use App\Models\User;
use App\Http\Resources\ShiftResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Exceptions\HttpResponseException;

class ShiftController extends Controller
{
	public function listPartner(Request $request) {
		$user = User::find($request->id);
		$shifts = $user->partnerShifts;

		return new JsonResponse([
			'result' => 'success',
			'list' => ShiftResource::collection($shifts)
		]);
	}

	public function listWorker(Request $request) {
		$user = User::find($request->id);
		$isOpen = $request->isOpen;
		$shifts = $user->workerShifts($isOpen);

		foreach($shifts as &$shift) {
			if ($isOpen)
				$shift['status'] = 0;
			else {
				if ($shift->status == 2) {
					if (strpos($shift->bookedMen, ','.$user->id.',') !== false)
						$shift['status'] = 1;
					else
						$shift['status'] = 2;
				}
				else
					$shift['status'] = 0;
			}
		}

		return new JsonResponse(['result' => 'success', 'list' => ShiftResource::collection($shifts)]);
	}

	//for Partners
	public function detail(Request $request) {
		$shift = Shift::find($request->id);
		$user = User::find($shift->partnerId);

		$shift['company'] = $user->companyName;
		$shift['rating'] = $user->rating;

		if ($request->workerId != null) {
			if (strpos($shift->bookedMen, ','.$request->workerId.',') !== false) {
				$shift['status'] = 1;
			}
			else if (strpos($shift->reservedMen, ','.$request->workerId.',') !== false) {
				$shift['status'] = 2;
			}
			else
				$shift['status'] = 0;
		}

		return (new ShiftResource($shift))->additional(['result' => 'success']);
	}

	public function post(Request $request) {
		Shift::create($request->all());
		return new JsonResponse(['result' => 'success']);
	}

	public function edit(Request $request) {
		$shift = Shift::find($request->id);

		$shift->update($request->only([
	        'title',
	        'type',
	        'location',
	        'startDate',
	        'endDate',
	        'workers',
	        'payRate',
	        'hours',
	        'fixedPay',
	        'desc',
	    ]));
		return new JsonResponse(['result' => 'success']);
	}

	public function delete(Request $request) {
		$shift = Shift::find($request->id);

		$shift->delete();
		return new JsonResponse(['result' => 'success']);
	}

	public function applicantsList(Request $request) {
		$shift = Shift::find($request->id);
		$booked = [];
		$reserved = [];
		$bookedMen = $shift->bookedMen;
		$reservedMen = $shift->reservedMen;

		if ($bookedMen != '') {
			$userids = explode(',', $shift->bookedMen);

			foreach($userids as $id) {
				if ($id == '')
					continue;

				$user = User::find($id);
				array_push($booked, $user->only(['id', 'firstName', 'lastName', 'headline']));
			}
		}

		if ($reservedMen != '') {
			$userids = explode(',', $shift->reservedMen);

			foreach($userids as $id) {
				if ($id == '')
					continue;

				$user = User::find($id);
				array_push($reserved, $user->only(['id', 'firstName', 'lastName', 'headline']));
			}
		}

		return new JsonResponse(['result' => 'success', 'booked' => $booked, 'reserved' => $reserved]);
	}

	public function promote(Request $request) {
		$shift = Shift::find($request->id);
		$bookedMen = $shift->bookedMen;
		$reservedMen = $shift->reservedMen;
		$workerId = ','.$request->workerId.',';

		if (($pos = strpos($reservedMen, $workerId)) !== false) {
			$reservedMen = substr_replace($reservedMen, '', $pos + 1, strlen($workerId) - 1);
			if ($reservedMen == ',')
				$reservedMen = '';

			if ($bookedMen == '')
				$bookedMen = $workerId;
			else
				$bookedMen = $bookedMen.$request->workerId.',';
		}
		else
			throw new HttpResponseException(response('', 500));

		$request['bookedMen'] = $bookedMen;
		$request['reservedMen'] = $reservedMen;
		$shift->update($request->only(['bookedMen', 'reservedMen']));

		return new JsonResponse(['result' => 'success']);
	}

	public function demote(Request $request) {
		$shift = Shift::find($request->id);
		$bookedMen = $shift->bookedMen;
		$reservedMen = $shift->reservedMen;
		$workerId = ','.$request->workerId.',';

		if (($pos = strpos($bookedMen, $workerId)) !== false) {
			$bookedMen = substr_replace($bookedMen, '', $pos + 1, strlen($workerId) - 1);
			if ($bookedMen == ',')
				$bookedMen = '';

			if ($reservedMen == '')
				$reservedMen = $workerId;
			else
				$reservedMen = $reservedMen.$request->workerId.',';
		}
		else
			throw new HttpResponseException(response('', 500));

		$request['bookedMen'] = $bookedMen;
		$request['reservedMen'] = $reservedMen;
		$shift->update($request->only(['bookedMen', 'reservedMen']));

		return new JsonResponse(['result' => 'success']);
	}

	//for Workers
	public function apply(Request $request) {
		$shift = Shift::find($request->id);
		$workerId = $request->workerId;
		$bookedMen = $shift->bookedMen;
		$reservedMen = $shift->reservedMen;
		$bookNumber = $bookedMen == '' ? 0 : count(explode(',', $bookedMen)) - 2;
		$status = 0; //0 if booked or 1 if reserved

		if ($bookNumber > $shift->workers) {
			if ($reservedMen == '')
				$reservedMen = ','.$workerId.',';
			else
				$reservedMen = $reservedMen.$workerId.',';
		}
		else {
			if ($bookedMen == '')
				$bookedMen = ','.$workerId.',';
			else
				$bookedMen = $bookedMen.$workerId.',';
		}

		$shift['bookedMen'] = $bookedMen;
		$shift['reservedMen'] = $reservedMen;
		$shift->update($shift->only(['bookedMen', 'reservedMen']));

		return new JsonResponse(['result' => 'success', 'status' => $status]);
	}

	public function cancel(Request $request) {
		$shift = Shift::find($request->id);
		$bookedMen = $shift->bookedMen;
		$reservedMen = $shift->reservedMen;
		$workerId = ','.$request->workerId.',';

		if (($pos = strpos($reservedMen, $workerId)) !== false) {
			$reservedMen = substr_replace($reservedMen, '', $pos + 1, strlen($workerId) - 1);
			if ($reservedMen == ',')
				$reservedMen = '';
		}
		else if (($pos = strpos($bookedMen, $workerId)) !== false) {
			$bookedMen = substr_replace($bookedMen, '', $pos + 1, strlen($workerId) - 1);
			if ($bookedMen == ',')
				$bookedMen = '';
		}
		else
			throw new HttpResponseException(response('', 500));

		$shift['bookedMen'] = $bookedMen;
		$shift['reservedMen'] = $reservedMen;
		$shift->update($shift->only(['bookedMen', 'reservedMen']));

		return new JsonResponse(['result' => 'success']);
	}
}