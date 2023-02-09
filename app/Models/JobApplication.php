<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'job_applications';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'job_id',
        'user_id',
        'resume_id',
        'applied_time',
        'is_shortlisted',
        'selected_time',
        'is_read',
        'read_receipt_time'
    ];

    /**
     * Get job information
     */
    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    /**
     * Get submitted user information
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
