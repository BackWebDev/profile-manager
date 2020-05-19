<?php

use Illuminate\Database\Seeder;
use App\Profile;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function generateRandomString($length = 10) {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function run()
    {
        for($i = 0; $i < 10; $i++){
            $profile = new Profile();
            $profile->name = $this->generateRandomString();
            $profile->save();
        }
    }
}
