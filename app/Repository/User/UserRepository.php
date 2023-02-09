<?php

namespace App\Repository\User;

use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use App\Interfaces\User\UserRepositoryInterface;
use App\Mail\VerifyEmail;
use App\Models\User;
use App\Models\UserNotificationSetting;
use App\Utils\Response;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class UserRepository implements UserRepositoryInterface
{
    use Response;

    /**
     * Retrieve all users
     *
     * @return JSONResponse
     */
    public function index()
    {
        try {
            $searchQuery = request()->input('s');
            $sort = request()->input('sort');
            $users = User::with(['skills', 'resumes'])
                ->when($searchQuery, function ($query, $searchQuery) { // Only runs if 's' parameter is set
                    $query->where('first_name', 'Like', '%' . $searchQuery . '%')
                        ->orWhere('last_name', 'Like', '%' . $searchQuery . '%');
                })
                ->when($sort, function ($query, $sort) {
                    // Sort by $sort text, i.e. if sort is 'first_name' sort by Ascending order, if '-first_name' sort by Descending order
                    $attribute = $sort;
                    $sort_order = 'ASC';
                    if (strncmp($sort, '-', 1) === 0) {
                        $sort_order = 'DESC';
                        $attribute = substr($sort, 1);
                    }
                    $query->orderBy($attribute, $sort_order);
                })
                ->paginate(10);

            return UserResource::collection($users);
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("There was a problem with fetching the data, try again."), "exception" => $th], 500);
        }
    }

    /**
     * Save a new user
     * 
     * @param mixed $request
     * @return JSONResponse
     */
    public function store(UserRequest $request)
    {
        try {
            $request['password'] = Hash::make($request->password);
            $user = User::create($request->only([
                'email',
                'password',
                'img_url',
                'first_name',
                'last_name',
                'date_of_birth',
                'profession',
                'short_bio'
            ]));

            if ($user->id) {
                UserNotificationSetting::create([
                    'user_id' => $user->id,
                    'shortlisted_notification' => true,
                    'inactivity_notification' => true
                ]);

                $verify =  DB::table('password_resets')->where([
                    ['email', $request->all()['email']]
                ]);

                if ($verify->exists()) {
                    $verify->delete();
                }

                $pin = rand(100000, 999999);

                DB::table('password_resets')
                    ->insert(
                        [
                            'email' => $request->all()['email'],
                            'token' => $pin,
                            'created_at' => Carbon::now()
                        ]
                    );
            }

            Mail::to($request->email)->send(new VerifyEmail($request->email, $pin));

            return new UserResource($user);
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("There was a problem with saving the user, please check and try again."), "exception" => $th], 500);
        }
    }

    /**
     * Update an existing user
     * 
     * @param mixed $request
     * @param mixed $user
     * @return JSONResponse
     */
    public function update(UserRequest $request, User $user)
    {
        try {
            $user->update($request->only(['email', 'img_url', 'first_name', 'last_name', 'date_of_birth', 'profession', 'short_bio']));

            return new UserResource($user);
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("There was a problem with updating the user, please try again."), "exception" => $th], 500);
        }
    }

    /**
     * Delete user from database
     * 
     * @param User $user
     * @return JSONResponse
     */
    public function destroy(User $user)
    {
        try {
            $user->forceDelete();

            return $this->responseData(null, __('User deleted successfully.'));
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("User deletion failed, try again."), "exception" => $th], 500);
        }
    }

    /**
     * Verify an email
     * 
     * @param mixed $request
     * @return JSONResponse
     */
    public function verifyEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['email', 'required'],
            'token' => ['required'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with(['message' => $validator->errors()]);
        }
        $select = DB::table('password_resets')
            ->where('email', $request->email)
            ->where('token', $request->token);

        if ($select->get()->isEmpty()) {
            return new JsonResponse(['success' => false, 'message' => __("Invalid PIN")], 400);
        }

        $select = DB::table('password_resets')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->delete();

        $user = User::where('email', $request->email)->first();
        $user->email_verified_at = Carbon::now();
        $user->save();

        return new JsonResponse(['success' => true, 'message' => __("Email is verified")], 200);
    }

    /**
     * Block user by soft deleting them
     * 
     * @param User $user
     * @return JSONResponse
     */
    public function  blockUser(User $user)
    {
        try {
            if ($user->blocked == true) {
                return $this->responseError(__("User already blocked."));
            }
            $user->blocked = true;
            $user->save();

            return $this->responseData(null, __('User Blocked successfully.'));
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("User block failed, try again."), "exception" => $th], 500);
        }
    }

    /**
     * Unblock user by soft deleting them
     * 
     * @param User $user
     * @return JSONResponse
     */
    public function  unBlockUser(User $user)
    {
        try {
            $user->blocked = false;
            $user->save();

            return $this->responseData(null, __('User Unblocked successfully.'));
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("User unblock failed, try again."), "exception" => $th], 500);
        }
    }
}
