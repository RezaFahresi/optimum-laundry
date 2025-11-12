<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduleLog extends Model
{
    protected $fillable = [
        'schedule_id',
        'user_id',
        'user_email',
        'status',
        'reason',
        'recipients',
        'error',
    ];

    protected $casts = [
        'recipients' => 'array',
    ];

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
