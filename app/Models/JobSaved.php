<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobSaved extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'job_saved';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'job_id',
        'user_id',
        'saved_time',
    ];
    
    /**
     * Get job information
     */
    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    /**
     * Get details of user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
