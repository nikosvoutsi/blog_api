<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function hospital()
    {
        return $this->belongsTo(Hospital::class, 'hospital_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'tag_to_post')->withTimestamps();
    }
    

    public function category()
{
    return $this->belongsTo(Category::class, 'category_id');
}


    public function creator()
    {
        return $this->belongsTo(User::class, 'author');
    }

    

    

}
