<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProfileImage extends Model
{
    protected $table = 'profile_images';

    protected $fillable = ['profile_id, profile_image_id'];
}
