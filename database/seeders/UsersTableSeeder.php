<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('users')->insert(array(
            [
                'name' => 'Dhiki Adriansyah',
                'email' => 'diki725@gmail.com',
                'password' => bcrypt('12345678'),
                'foto' => 'user.png',
                'level' => 1
            ],
            [
                'name' => 'Anisa Nabil',
                'email' => 'nabil@gmail.com',
                'password' => bcrypt('12345678'),
                'foto' => 'user.png',
                'level' => 2
            ]
        ));
    }
}
