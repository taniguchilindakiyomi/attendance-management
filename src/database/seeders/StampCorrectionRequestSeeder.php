<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StampCorrectionRequest;
use App\Models\User;
use App\Models\Attendance;

class StampCorrectionRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::where('email', 'user@example.com')->first();
        $admin = User::where('email', 'admin@example.com')->first();
        $attendance = Attendance::where('user_id', $user->id)->first();

        StampCorrectionRequest::create([
            'user_id' => $user->id,
            'attendance_id' => $attendance->id,
            'admin_id' => $admin->id,
            'requested_start_time' => now()->subHours(8),
            'requested_end_time' => now()->subHours(1),
            'requested_breaks' => json_encode([['start' => now()->subHours(4), 'end' => now()->subHours(3)]]),
            'reason' => '打刻ミスのため修正希望',
            'remarks' => '管理者確認済み',
            'status' => 'pending',
        ]);
    }
}
