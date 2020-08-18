<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $counter = 1;
        $userName = env("USER_{$counter}_NAME");
        $userEmail = env("USER_{$counter}_EMAIL");
        $userPass = env("USER_{$counter}_PASS");
        $roleId = $this->getRoleId(env("USER_{$counter}_ROLE"));

        while ($userName && $userEmail && $userPass) {
            $user = \App\User::firstOrNew(['email' => $userEmail]);
            if (!$user->exists) {
                $user->fill([
                    'name'              => $userName,
                    'email'             => $userEmail,
                    'email_verified_at' => new \Carbon\Carbon('2017-07-07 07:07:07'),
                    'password'          => bcrypt($userPass),
                    'remember_token'    => Str::random(60),
                    'created_at'        => new \Carbon\Carbon('2017-07-07 07:07:07'),
                    'updated_at'        => new \Carbon\Carbon('2017-07-07 07:07:07'),
                ])->save();
            }


            $counter++;
            $userName = env("USER_{$counter}_NAME");
            $userEmail = env("USER_{$counter}_EMAIL");
            $userPass = env("USER_{$counter}_PASS");
            $roleId = $this->getRoleId(env("USER_{$counter}_ROLE"));
        };
    }


    private function getRoleId($roleName)
    {
        $roleId = null;
        try {
            // $roleId = Role::where('name', $roleName)->firstOrFail()->id;
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        }

        return $roleId;
    }
}
