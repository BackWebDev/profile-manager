<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\{CreateNewProfile, UpdateProfile};
use App\{Profile,ProfileImage, Image};
use Illuminate\Support\Str;

class ProfilesController extends Controller
{
    public function allProfiles() 
    {
        //find all profiles and their relations to images
        $profiles = Profile::with('images')->get()->transform(function($profile) {
            return [
                'profile' => $profile->name,
                'images'  => $profile->images->pluck('img_path'),
                'actions' => $profile->id
            ];
        });

        return response()->json([
            "profiles" => $profiles
        ], 200);
    
    }

    public function createProfile(CreateNewProfile $request)
    {
        if($request->name){
            $profile = Profile::create([
                'name' => $request->name,
            ]);
            $profile->save();
            
            //path to save images
            $destinationPath = public_path('/images');

            if($request->images){
                //add images to the database and create relation to specific profiles
                foreach($request->images as $image){
                    $name = Str::random(15).'.'.$image->getClientOriginalExtension();
                    $image->move($destinationPath, $name);
                    $images[] = $name;
                    $image = new Image;
                    $image->img_path = $name;
                    $image->save();

                    $profile_image = new ProfileImage;
                    $profile_image->profile_id = $profile->id;
                    $profile_image->profile_image_id = $image->id;
                    $profile_image->save();
                }
            }

        }
        else{
            return response()->json(["success" => false, "message" => 'Profile creation failed'], 500);
        }

        return response()->json(["success" => true, "message" => 'Profile creation succeeded']);
    }

    public function editProfile($id)
    {
        $profile = Profile::find($id)->name;
        $images = Profile::find($id)->images->pluck('id','img_path');
        $imageWithUrl = [];
        foreach($images as $key => $image){
            $imageWithUrl[] = [
                'url' => env('APP_URL') . '/images/' . $key,
                'id' => $image,
            ]; 
        }
        $profile_images = [
            'name'   => $profile,
            'images' => $imageWithUrl
        ];
        return response()->json([
            "profiles" => $profile_images
        ], 200);
    }

    public function updateProfile(UpdateProfile $request)
    {
        $profile = Profile::find($request->profile_id);
        $profile->name = $request->name;
        $profile->save();

        if($request->deleted_images){
            //delete profile-related images
            foreach($request->deleted_images as $image){
                $profile_image = ProfileImage::where('profile_id', $profile->id)->where('profile_image_id', $image)->delete();
            } 
        }
        $destinationPath = public_path('/images');
        if($request->images){
            //add images to the database and create relation to specific profiles
            foreach($request->images as $image){
                $name = Str::random(15).'.'.$image->getClientOriginalExtension();
                $image->move($destinationPath, $name);
                $images[] = $name;
                $image = new Image;
                $image->img_path = $name;
                $image->save();

                $profile_image = new ProfileImage;
                $profile_image->profile_id = $profile->id;
                $profile_image->profile_image_id = $image->id;
                $profile_image->save();
            }
        }
        return response()->json(["success" => true, "message" => 'Profile updated succeeded']);
    }

    public function deleteProfile(Request $request)
    {
        $profile = Profile::findOrFail($request->id);
        $profile->images()->detach();
        $profile->delete();

        //returns json
        return $this->allProfiles();
    }
}
