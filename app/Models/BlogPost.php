<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'blog_posts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'post_title',
        'slug',
        'featured_image',
        'author_id',
        'is_featured',
        'is_published',
        'excerpt',
        'body'
    ];

    /**
     * Posted by admin user
     */
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'author_id');
    }
}
