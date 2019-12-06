<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class userSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permission1 = Permission::create(['name'=>'pos']);
        $permission2 = Permission::create(['name'=>'conf']);
        $permission3 = Permission::create(['name'=>'report']);
        $roleAdmin = Role::create(['name'=>'Super Admin']);
        $roleAdmin->givePermissionTo($permission2);

        $role = Role::create(['name'=>'Business Admin']);
        $role->givePermissionTo($permission1);
        $role->givePermissionTo($permission2);
        $role->givePermissionTo($permission3);
        $role = Role::create(['name'=>'Merchant Admin']);
        $role->givePermissionTo($permission1);
        $role->givePermissionTo($permission2);
        $role->givePermissionTo($permission3);
        $role= Role::create(['name'=>'Cashier']);
        $role->givePermissionTo($permission1);
        $role->givePermissionTo($permission3);

        $user = \App\User::Create([
            'name' => 'admin',
            'username' => 'admin',
            'password' => Hash::make('admin'),
            'ojdb_pruser'=>1,
            'created_by'=>1,
            'api_token' => \Illuminate\Support\Str::random(60)
        ]);

        $user->assignRole($roleAdmin);
        $user->assignRole($role);


        \App\machine_type::Create([
            'description' => 'Cashier POS'
        ]);

        \App\machine_type::Create([
            'description' => 'Ordering Monitor'
        ]);
    }
}
