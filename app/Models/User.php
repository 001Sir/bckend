<?php

namespace App\Models;

use App\Notifications\ResetPasswordNotification;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Shift;

class User extends Authenticatable implements CanResetPassword
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'email',
        'firstName',
        'lastName',
        'phoneNumber',
        'birthyear',
        'address',
        'headline',
        'companyName',
        'EIN',
        'companyLocation',
        'rating',
        'isWorker',
        'password'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password'
    ];

    /**
     * Send a password reset notification to the user.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
    }

    public function partnerShifts() {
        return $this->hasMany(Shift::class, 'partnerId');
    }

    public function workerShifts($isOpen) {
        $userid = $this->id;
        $shifts = null;

        if ($isOpen)
            $shifts = Shift::where('status', 2)->where('bookedMen', 'NOT LIKE', "%,{$userid},%")->where('reservedMen', 'NOT LIKE', "%,{$userid},%");
        else
            $shifts = Shift::where(function($query) use($userid){
                $query->where('bookedMen', 'LIKE', "%,{$userid},%");
                $query->orWhere('reservedMen', 'LIKE', "%,{$userid},%");
            })->where('status', '!=', 0);

        return $shifts->get();
    }
}
