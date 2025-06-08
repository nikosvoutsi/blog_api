<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Ποια posts ανήκουν σε αυτή την κατηγορία
     public function posts()
    {
        return $this->hasMany(Post::class);
    }


    // Ποια είναι η γονική κατηγορία
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // Ποιες είναι οι υποκατηγορίες
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
}
