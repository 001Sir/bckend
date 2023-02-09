<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'packages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'price',
        'subscription_type',
        'is_active'
    ];

    /**
     * List all Features
     */
    public function features()
    {
        return $this->belongsToMany(Feature::class, 'package_feature');
    }

    /**
     * Subscribed partners
     */
    public function partners()
    {
        return $this->belongsToMany(Partner::class, 'partner_package')->withTimestamps();
    }
}
