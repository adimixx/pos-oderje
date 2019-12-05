<?php

use Illuminate\Database\Seeder;

class userSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = \App\User::Create([
            'name' => 'admin',
            'username' => 'admin',
            'password' => Hash::make('admin'),
            'ojdb_pruser'=>1,
            'created_by'=>1,
            'api_token' => \Illuminate\Support\Str::random(60)
        ]);

        \App\machine_type::Create([
            'description' => 'Cashier POS'
        ]);

        \App\machine_type::Create([
            'description' => 'Ordering Monitor'
        ]);
    }
}
