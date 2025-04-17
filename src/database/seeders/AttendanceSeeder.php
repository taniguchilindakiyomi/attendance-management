<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attendance;
use App\Models\User;


class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::where('email', 'user@example.com')->first();

        Attendance::create([
            'user_id' => $user->id,
            'start_time' => now()->subHours(9),
            'end_time' => now(),
            'status' => '退勤済',
        ]);
    }
}
