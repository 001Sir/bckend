<?php

namespace App\Models;

use App\Notifications\ResetPartnerPasswordNotification;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Partner extends Authenticatable implements CanResetPassword
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'partners';

    /**
     * The authentication guard associated with the model.
     *
     * @var string
     */
    protected $guard = 'partners';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'img_url',
        'company_name',
        'short_name',
        'email',
        'password',
        'bio',
        'employee_count'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Send a password reset notification to the user.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $url = env("STATIC_WEB_URL"). "/partner/reset-password?token=" .$token;
        $this->notify(new ResetPartnerPasswordNotification($url));
    }

    /**
     * Get details of job posts
     */
    public function jobs()
    {
        return $this->hasMany(Job::class);
    }

    /**
     * Subscribed package
     */
    public function package()
    {
        return $this->belongsToMany(Package::class, 'partner_package')->withPivot('valid_until')->withTimestamps();
    }
}
