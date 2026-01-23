<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    // Enables soft delete functionality (records are not permanently removed)
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     * These fields can be safely filled using create() or update().
     */
    protected $fillable = [
        'title', 'description', 'is_done',
    ];

    /**
    * Attribute casting definitions.
    * Automatically converts attributes to native PHP types.
    */
    protected $casts = [
        'is_done' => 'boolean',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
