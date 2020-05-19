<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $table = 'images';
    
    protected $fillable = ['profile_id'];

    public function profiles()
    {
        return $this->belongsToMany( Profile::class, 'profile_images', 'profile_id', 'profile_image_id');
    }
}
