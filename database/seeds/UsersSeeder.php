<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user1 = DB::table('users')->insertGetId([
            'name' => 'Chrystian Freitas',
            'email' => 'chrystian.felipe.freitas@gmail.com',
            'password' => Hash::make('123'),
            //'token_api' => Hash::make('0001'.'000012345'. Str::random(10)),
        ]);

        DB::table('bank_accounts')->insert([
            'user_id' => $user1,
            'agency' => '0001',
            'agency_dv' => '1',
            'number_account' => '000012345',
            'number_account_dv' => '2',
            'balance' => '1000',
        ]);

        $user2 = DB::table('users')->insertGetId([
            'name' => 'João Rodrigues',
            'email' => 'joao@gmail.com',
            'password' => Hash::make('123'),
            //'token_api' => Hash::make('0001'.'000054321'. Str::random(10))
        ]);

        DB::table('bank_accounts')->insert([
            'user_id' => $user2,
            'agency' => '0001',
            'agency_dv' => '1',
            'number_account' => '000054321',
            'number_account_dv' => '2',
        ]);
    }
}
