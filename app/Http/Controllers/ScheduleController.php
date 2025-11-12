<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\ScheduleLog;


class ScheduleController extends Controller
{
    public function runSchedules()
    {
        $today = strtolower(now()->translatedFormat('l')); // senin, selasa, dst
        $date  = now()->toDateString();

        $schedules = Schedule::with('user')
            ->where('status', 'active')
            ->where(function ($q) use ($date) {
                $q->whereNull('end_date')
                    ->orWhere('end_date', '>=', $date);
            })
            // ambil hanya yg belum pernah dijalankan hari ini
            ->where(function ($q) use ($date) {
                $q->whereNull('last_run')
                    ->orWhereDate('last_run', '<', $date);
            })
            ->get();


        // ambil admin
        $admin = User::where('role', 1)->first();

        $results = [];

        foreach ($schedules as $schedule) {
            $rules = is_string($schedule->rules) ? json_decode($schedule->rules, true) : $schedule->rules;
            $shouldRun = false;
            $reason = 'skip';

            switch ($schedule->type) {
                case 'daily':
                    $shouldRun = true;
                    $reason = 'daily schedule';
                    break;

                case 'weekly':
                    if (!empty($rules['weekly']) && in_array($today, $rules['weekly'])) {
                        $shouldRun = true;
                        $reason = "weekly match ($today)";
                    } else {
                        $reason = 'weekly not match';
                    }
                    break;

                case 'monthly':
                    $dayOfMonth = now()->day;
                    if (!empty($rules['monthly']) && in_array($dayOfMonth, $rules['monthly'])) {
                        $shouldRun = true;
                        $reason = "monthly match (day $dayOfMonth)";
                    } else {
                        $reason = 'monthly not match';
                    }
                    break;

                case 'custom':
                    if (!empty($rules['custom']) && in_array($date, $rules['custom'])) {
                        $shouldRun = true;
                        $reason = "custom match ($date)";
                    } else {
                        $reason = 'custom not match';
                    }
                    break;
            }

            if ($shouldRun && $schedule->user) {
                try {
                    $recipients = [$schedule->user->email];
                    if ($admin) {
                        $recipients[] = $admin->email;
                    }

                    Mail::to($recipients)->send(new \App\Mail\ScheduleReminder($schedule));

                    $schedule->last_run = now();
                    $schedule->save();

                    $logData = [
                        'schedule_id' => $schedule->id,
                        'user_id'     => $schedule->user->id,
                        'user_email'  => $schedule->user->email,
                        'status'      => 'email sent',
                        'reason'      => $reason,
                        'recipients'  => $recipients,
                    ];

                    ScheduleLog::create($logData);

                    $results[] = $logData;
                } catch (\Exception $e) {
                    $logData = [
                        'schedule_id' => $schedule->id,
                        'user_id'     => $schedule->user->id ?? null,
                        'user_email'  => $schedule->user->email ?? '-',
                        'status'      => 'failed',
                        'reason'      => $reason,
                        'error'       => $e->getMessage(),
                    ];

                    ScheduleLog::create($logData);

                    $results[] = $logData;
                }
            } else {
                $logData = [
                    'schedule_id' => $schedule->id,
                    'user_id'     => $schedule->user->id ?? null,
                    'user_email'  => $schedule->user->email ?? '-',
                    'status'      => 'skipped',
                    'reason'      => $reason,
                ];

                ScheduleLog::create($logData);

                $results[] = $logData;
            }
        }

        return response()->json([
            'date'     => $date,
            'today'    => $today,
            'total'    => $schedules->count(),
            'results'  => $results,
        ]);
    }
}
