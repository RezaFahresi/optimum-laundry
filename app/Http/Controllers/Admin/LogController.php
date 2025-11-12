<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TransactionLog;
use App\Models\ScheduleLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $currentMonth = $request->input('month', date('m'));
        $currentYear  = $request->input('year', date('Y'));

        // Ambil semua log transaksi dengan filter bulan & tahun
        $transactionLogs = TransactionLog::with('user')
            ->when($currentYear, function ($q) use ($currentYear) {
                $q->whereYear('created_at', $currentYear);
            })
            ->when($currentMonth, function ($q) use ($currentMonth) {
                $q->whereMonth('created_at', $currentMonth);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Ambil semua log schedule dengan filter bulan & tahun
        $scheduleLogs = ScheduleLog::with('schedule', 'schedule.user')
            ->when($currentYear, function ($q) use ($currentYear) {
                $q->whereYear('created_at', $currentYear);
            })
            ->when($currentMonth, function ($q) use ($currentMonth) {
                $q->whereMonth('created_at', $currentMonth);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Ambil daftar tahun untuk dropdown filter
        $years = TransactionLog::selectRaw('YEAR(created_at) as Tahun')
            ->distinct()
            ->orderBy('Tahun', 'desc')
            ->get();

        return view('admin/logs', compact(
            'transactionLogs',
            'scheduleLogs',
            'currentMonth',
            'currentYear',
            'years',
            'user'
        ));
    }
}
