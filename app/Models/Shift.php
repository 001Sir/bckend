<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Shift extends Model
{
    use HasApiTokens, HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'shifts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'title',
        'type',
        'location',
        'startDate',
        'endDate',
        'workers',
        'bookedMen',
        'reservedMen',
        'payRate',
        'hours',
        'fixedPay',
        'desc',
        'created_at',
        'status',
        'partnerId'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
    ];

    protected $casts = [
        'created_at' => 'datetime:M j-g:i a',
        'endDate' => 'datetime:M j-g:i a',
        'startDate' => 'datetime:M j-g:i a',
    ];
}
