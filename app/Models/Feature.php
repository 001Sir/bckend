<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'features';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'config_name',
        'name',
        'display_only',
    ];

    /**
     * List all Packages
     */
    public function packages()
    {
        return $this->belongsToMany(Package::class, 'package_feature');
    }
}
