<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StampCorrectionRequest;
use Illuminate\Support\Facades\Auth;

class StampCorrectionRequestController extends Controller
{
    public function requestList(Request $request)
    {
        if (Auth::guard('admin')->check()) {
            $admin = Auth::guard('admin')->user();
            $status = $request->query('status', 'pending');

            $requests = StampCorrectionRequest::with(['user', 'attendance'])
                ->when($status === 'pending', fn($query) => $query->where('status', 'pending'))
                ->when($status === 'approved', fn($query) => $query->where('status', 'approved'))
                ->get();

        return view('/admin/request/list', compact('requests', 'status'));
    }

        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            $status = $request->query('status', 'pending');

            $requests = StampCorrectionRequest::with('attendance')
                ->where('user_id', $user->id)
                ->where('status', $status)
                ->get();

            return view('request/list', compact('requests', 'status'));
        }

        return redirect('/login');
    }


}