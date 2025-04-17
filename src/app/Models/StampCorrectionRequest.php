<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StampCorrectionRequest extends Model
{
    use HasFactory;

    protected $table = 'stamp_correction_requests';

    protected $fillable = [
        'user_id',
        'attendance_id',
        'admin_id',
        'requested_start_time',
        'requested_end_time',
        'requested_break_start',
        'requested_break_end',
        'remarks',
        'status',
        'approved_at',
    ];


    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }

    public function admin()
    {
        return $this->belongsTo(\App\Models\Admin::class, 'admin_id')->withDefault();
    }

}
