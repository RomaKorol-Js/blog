<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'Невідомий автор',
                'email' => 'author_unknown@g.g',
                'password' => bcrypt(Str::random(16)), // password
            ],
            [
                'name' => 'Автор',
                'email' => 'author1@g.g',
                'password' => bcrypt('123123'), // password 123123
            ],
        ];
        DB::table('users')->insert($data);
        //
    }
}
