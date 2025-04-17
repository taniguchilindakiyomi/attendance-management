<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\BreakTime;
use App\Models\StampCorrectionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UserCorrectionRequest;

class AttendanceController extends Controller
{
    public function getAttendance()
    {

        $user = Auth::user();

        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('start_time', now()->toDateString())
            ->first();

        $status = $attendance ? $attendance->status : '勤務外';


        return view('/attendance/create', compact('status', 'attendance'));
    }



    public function startAttendance(Request $request)
    {
        $user = Auth::user();

        $existingAttendance = Attendance::where('user_id', $user->id)
        ->whereDate('start_time', now()->toDateString())
        ->first();

        if ($existingAttendance) {
            return redirect()->back();

        }

        Attendance::create([
            'user_id' => $user->id,
            'start_time' => now(),
            'status' => '出勤中',
        ]);

        return redirect('/attendance');
    }


    public function startBreak()
    {
        $user = Auth::user();

        $attendance = Attendance::where('user_id', $user->id)
        ->whereDate('start_time', now()->toDateString())
        ->first();

        if (!$attendance || $attendance->status !== '出勤中') {
            return redirect()->back();
        }


        BreakTime::create([
            'attendance_id' => $attendance->id,
            'break_start' => now(),
        ]);

        $attendance->update(['status' => '休憩中']);

        return redirect('/attendance');
    }



    public function endBreak()
    {
        $user = Auth::user();

        $attendance = Attendance::where('user_id', $user->id)
        ->whereDate('start_time', now()->toDateString())
        ->first();

        if (!$attendance || $attendance->status !== '休憩中') {
        return redirect()->back();
        }

        $break = BreakTime::where('attendance_id', $attendance->id)
        ->whereNull('break_end')
        ->latest()
        ->first();

        if ($break) {
            $break->update(['break_end' => now()]);
        }

        $attendance->update(['status' => '出勤中']);

        return redirect('/attendance');

    }


    public function endAttendance()
    {
        $user = Auth::user();

        $attendance = Attendance::where('user_id', $user->id)
        ->whereDate('start_time', now()->toDateString())
        ->first();

        if (!$attendance || $attendance->status === '退勤済') {
            return redirect()->back();
        }

        if ($attendance->status !== '出勤中') {
            return redirect()->back();
        }

        $attendance->update([
        'end_time' => now(),
        'status' => '退勤済',
        ]);

        return redirect('/attendance');
    }



    public function getAttendanceList(Request $request)
    {
        $user = Auth::user();

        $month = $request->input('month', now()->format('Y-m'));

        $attendances = Attendance::where('user_id', $user->id)
        ->whereYear('start_time', substr($month, 0, 4))
        ->whereMonth('start_time', substr($month, 5, 2))
        ->get();


        return view('/attendance/list', compact('attendances', 'month'));
    }




    public function attendanceDetail($id)
    {
        $user = Auth::user();

        $attendance = Attendance::with('breaks')->where('user_id', $user->id)->find($id);

        if (!$attendance) {
            return redirect()->back();
        }


        $stampRequest = StampCorrectionRequest::where('attendance_id', $attendance->id)->where('user_id', $user->id)->where('status', 'pending')->first();


        return view('/attendance/detail', compact('attendance', 'stampRequest'));
    }




    public function correctionAttendance(UserCorrectionRequest $request)
    {
        $user = Auth::user();

        $attendance = Attendance::find($request->attendance_id);

        if (!$attendance) {
            return redirect()->back();
        }

        StampCorrectionRequest::create([
        'user_id' => $user->id,
        'attendance_id' => $attendance->id,
        'requested_start_time' => $request->requested_start_time,
        'requested_end_time' => $request->requested_end_time,
        'requested_break_start' => $request->requested_break_start,
        'requested_break_end' => $request->requested_break_end,
        'remarks' => $request->remarks,
        'status' => 'pending',
        ]);

        return redirect("/attendance/{$attendance->id}");
    }






}
