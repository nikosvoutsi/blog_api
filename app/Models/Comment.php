<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    /**
     * Ο χρήστης που έκανε το σχόλιο
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Το post στο οποίο ανήκει το σχόλιο
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
