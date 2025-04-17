<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => '管理者ユーザー',
            'email' => 'test@icloud.com',
            'password' => Hash::make('kkkkkkkk'),
            'role' => 'admin',
        ]);
    }
}
