<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StampCorrectionRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use App\Models\User;
use App\Http\Requests\AdminCorrectionRequest;
use App\Models\BreakTime;
use Carbon\Carbon;
use App\Http\Requests\StampCorrectionRequestController;





class AdminController extends Controller
{



    public function detailAdmin($id)
    {
        $attendance = Attendance::with('user')->findOrFail($id);

        return view('admin.attendance.detail', compact('attendance'));
    }



    public function updateAdmin(AdminCorrectionRequest $request, $id)
    {
      $attendance = Attendance::findOrFail($id);

       $date = str_replace('　', ' ', $request->start_date);
    $datetimeFormat = 'Y年 n月j日 H:i';

    $start_time = Carbon::createFromFormat($datetimeFormat, $date . ' ' . $request->start_time);
    $end_time = Carbon::createFromFormat($datetimeFormat, $date . ' ' . $request->end_time);

    $attendance->update([
        'start_time' => $start_time,
        'end_time' => $end_time,
        'remarks' => $request->remarks,
    ]);

    $attendance->breaks()->delete();

    foreach ($request->breaks as $breakData) {
        $break_start = Carbon::createFromFormat($datetimeFormat, $date . ' ' . $breakData['break_start']);
        $break_end = $breakData['break_end']
            ? Carbon::createFromFormat($datetimeFormat, $date . ' ' . $breakData['break_end'])
            : null;

        $attendance->breaks()->create([
            'break_start' => $break_start,
            'break_end' => $break_end,
        ]);
    }

        return redirect("admin/attendance/{$attendance->id}");
    }


    public function attendanceList(Request $request)
    {

        $date = $request->get('date', now()->format('Y-m-d'));

        $attendances = Attendance::with('user')
        ->whereDate('start_time', $date)
        ->orderBy('start_time')
        ->get();


        return view('admin.attendance.list', compact('date', 'attendances'));
    }


    public function staffAttendanceList()
    {
        $users = User::all();
        return view('admin.staff.list', compact('users'));
    }



    public function monthlyAttendance(Request $request, $id)
    {

        $user = User::with('attendances')->findOrFail($id);

        $month = $request->get('month', now()->format('Y-m'));

        $attendances = $user->attendances()
        ->whereBetween('start_time', [
            \Carbon\Carbon::parse($month)->startOfMonth(),
            \Carbon\Carbon::parse($month)->endOfMonth()
        ])->orderBy('start_time')->get();

        return view('admin.attendance.staff-list', compact('user', 'attendances', 'month'));
    }



    public function getApprove($id)
    {

        $attendanceRequest = StampCorrectionRequest::find($id);

        if (!$attendanceRequest) {
        return redirect()->back();
        }

        return view('admin.request.approve', compact('attendanceRequest'));
    }

    public function postApprove($id)
    {
        $request = StampCorrectionRequest::find($id);

        if (!$request) {
        return redirect()->back();
        }

        $attendance = $request->attendance;

        $attendanceDate = \Carbon\Carbon::parse($attendance->start_time)->format('Y-m-d');


        $attendance->update([
        'start_time' => $attendanceDate . ' ' . $request->requested_start_time,
        'end_time' => $attendanceDate . ' ' . $request->requested_end_time,
        ]);


            BreakTime::where('attendance_id', $attendance->id)->delete();
        if ($request->requested_break_start && $request->requested_break_end) {
        BreakTime::create([
            'attendance_id' => $attendance->id,
            'break_start' => $attendanceDate . ' ' . $request->requested_break_start,
            'break_end' => $attendanceDate . ' ' . $request->requested_break_end,
        ]);
    }


        $request->update([
        'status' => 'approved',
        'admin_id' => Auth::id(),
        'approved_at' => now()
        ]);


        return redirect("/stamp_correction_request/approve/{id}");
    }




    public function requestAdmin(Request $request)
    {
        $status = $request->get('status', 'pending');

        $requests = StampCorrectionRequest::with(['user', 'attendance'])
                    ->when($status === 'pending', function ($query) {
                        $query->where('status', 'pending');
                    })
                    ->when($status === 'approved', function ($query) {
                        $query->where('status', 'approved');
                    })
                    ->get();


        return view('admin.request.list', compact('requests', 'status'));
    }




}
