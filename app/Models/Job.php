<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'jobs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'partner_id',
        'job_title',
        'is_remote',
        'category_id',
        'city',
        'country',
        'no_pay_range',
        'min_salary_range',
        'max_salary_range',
        'job_type_id',
        'desc',
        'job_active_duration',
        'is_published',
        'expiration_date',
        'status'
    ];

    public function getCreatedAtAttribute($date)
    {
        return Carbon::createFromDate($date)->format('Y-m-d H:i:s');
    }

    /**
     * Get category information
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get details of partner that posted the job advert.
     */
    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    /**
     * Get details of post duration
     */
    public function jobtype()
    {
        return $this->belongsTo(JobType::class, 'job_type_id', 'id');
    }

    /**
     * List out all applications
     */
    public function applicants()
    {
        return $this->hasMany(JobApplication::class);
    }

    /**
     * List all Skills
     */
    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'job_skill');
    }
}
