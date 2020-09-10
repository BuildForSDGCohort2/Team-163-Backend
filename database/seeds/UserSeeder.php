<?php

use App\Role;
use App\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role_id' => 1,
            'uuid' => '82-28393',
        ]);

        Role::create(['id' => 1, 'name' => 'Administrator']);
        Role::create(['id' => 2, 'name' => 'User']);
    }
}
