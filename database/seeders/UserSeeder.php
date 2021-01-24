<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run()
    {
        $counter = 1;;
        $carbon = new \Carbon\Carbon('2017-07-07 07:07:07');

        while ($userDef = env('USER_'.($counter++))) {
            [$name, $email, $pass] = array_map('trim', explode(',', $userDef, 3));
            $user = User::firstOrNew(['email' => $email]);
            if (!$user->exists) {
                $user->fill([
                    'name'              => $name,
                    'email'             => $email,
                    'email_verified_at' => $carbon,
                    'password'          => Hash::make($pass),
                    'remember_token'    => Str::random(60),
                    'created_at'        => $carbon,
                    'updated_at'        => $carbon,
                ])->save();
            }
        }

        if (config('app.env') === 'local') {
            User::factory(10)->create();
        }
    }
}
