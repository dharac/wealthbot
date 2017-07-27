<?php

use Illuminate\Database\Seeder;
use App\Role;

class RoleUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $owner = new Role();
        $owner->name         = 'admin';
        $owner->display_name = 'Administrator';
        $owner->description  = 'Administrator';
        $owner->save();

        $admin = new Role();
        $admin->name         = 'sub_admin';
        $admin->display_name = 'Sub Administrator';
        $admin->description  = 'Sub Administrator';
        $admin->save();

        $admin = new Role();
        $admin->name         = 'user';
        $admin->display_name = 'User';
        $admin->description  = 'User';
        $admin->save();
    }
}
