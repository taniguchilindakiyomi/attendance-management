<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AdminController;
use Laravel\Fortify\Fortify;
use App\Http\Controllers\StampCorrectionRequestController;

use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Illuminate\Http\Request;




/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/




Route::middleware(['auth:web', 'user'])->group(function () {

    Route::get('/attendance', [AttendanceController::class, 'getAttendance']);
    Route::post('/attendance/start', [AttendanceController::class, 'startAttendance']);
    Route::post('/attendance/break/start', [AttendanceController::class, 'startBreak']);
    Route::post('/attendance/break/end', [AttendanceController::class, 'endBreak']);
    Route::post('/attendance/end', [AttendanceController::class, 'endAttendance']);

    Route::get('/attendance/list', [AttendanceController::class, 'getAttendanceList']);

    Route::get('/attendance/{id}', [AttendanceController::class, 'attendanceDetail']);
    Route::post('/attendance/{id}', [AttendanceController::class, 'correctionAttendance']);


});



Route::middleware(['auth:admin', 'admin'])->group(function () {

    Route::get('/admin/attendance/list', [AdminController::class, 'attendanceList']);


    Route::get('/admin/staff/list', [AdminController::class, 'staffAttendanceList']);

    Route::get('/admin/attendance/{id}', [AdminController::class, 'detailAdmin']);
    Route::put('/admin/attendance/{id}', [AdminController::class, 'updateAdmin']);

    Route::get('/stamp_correction_request/approve/{id}', [AdminController::class, 'getApprove']);
    Route::post('/stamp_correction_request/approve/{id}', [AdminController::class, 'postApprove']);


    Route::get('/admin/attendance/staff/{id}', [AdminController::class, 'monthlyAttendance']);

});

Route::get('/stamp_correction_request/list', [StampCorrectionRequestController::class, 'requestList'])->middleware('admin_or_user');



Route::middleware(['web'])->group(function () {
    Route::get('/admin/login', [AuthenticatedSessionController::class, 'create']);
    Route::post('/admin/login', [AuthenticatedSessionController::class, 'store']);
    Route::post('/admin/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/admin/login');
    });
});

