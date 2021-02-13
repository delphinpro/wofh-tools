<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Orchid\Platform\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $this->makeRole('Administrator', 'admin', [
            "platform.index"              => true,
            "platform.systems.index"      => true,
            "platform.systems.roles"      => true,
            "platform.systems.users"      => true,
            "platform.systems.attachment" => true,
        ]);
    }

    private function makeRole(string $name, string $slug, $permissions = null)
    {
        if (!Role::whereSlug($slug)->first()) {
            $role = new Role();
            $role->name = $name;
            $role->slug = $slug;
            $role->permissions = $permissions;
            $role->save();
        }
    }
}
