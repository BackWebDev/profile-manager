<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $table = 'profiles';

    protected $fillable = ['name'];

    public function images()
    {
        return $this->belongsToMany( Image::class, 'profile_images', 'profile_id', 'profile_image_id');
    }
}
