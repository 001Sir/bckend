<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'skills';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'is_hinted'
    ];

    /**
     * List all jobs in skills
     */
    public function jobs()
    {
        return $this->belongsToMany(Job::class, 'job_skill');
    }

    /**
     * List all users with skill
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'skill_user');
    }
}
