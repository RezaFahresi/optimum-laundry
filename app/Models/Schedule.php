<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Schedule extends Model
{
    use HasFactory;

    protected $table = 'schedules';

    protected $fillable = [
        'user_id',
        'type',
        'start_date',
        'end_date',
        'last_run',
        'last_proccess',
        'time',
        'order_details',
        'total_amount',
        'service_type_id',
        'voucher_id',
        'pickup_option',
        'rules',
        'status',
    ];

    protected $casts = [
        'order_details' => 'array',
        'rules'         => 'array',
        'time'          => 'string',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function serviceType()
    {
        return $this->belongsTo(ServiceType::class, 'service_type_id');
    }

    public function voucher()
    {
        return $this->belongsTo(Voucher::class, 'voucher_id');
    }

    /**
     * Cek apakah hari ini ada jadwal penjemputan untuk schedule ini
     *
     * @return bool
     */
    public function cekJadwalHarian(): bool
    {
        $todayDate = Carbon::today()->toDateString(); // YYYY-MM-DD
        $todayDay  = strtolower(Carbon::today()->translatedFormat('l')); // senin, selasa, dst
        $dayOfMonth = Carbon::today()->day;

        // jadwal hanya valid jika aktif
        if ($this->status !== 'active') {
            return false;
        }

        // tanggal mulai / berakhir
        if ($this->start_date && $todayDate < $this->start_date) {
            return false;
        }

        if ($this->end_date && $todayDate > $this->end_date) {
            return false;
        }

        $rules = $this->rules ?? [];

        switch ($this->type) {
            case 'daily':
                return true;

            case 'weekly':
                return !empty($rules['weekly']) && in_array($todayDay, array_map('strtolower', $rules['weekly']));

            case 'monthly':
                return !empty($rules['monthly']) && in_array($dayOfMonth, $rules['monthly']);

            case 'custom':
                return !empty($rules['custom']) && in_array($todayDate, $rules['custom']);

            default:
                return false;
        }
    }

    /**
     * Static helper untuk cek apakah user tertentu punya jadwal hari ini
     *
     * @param int $userId
     * @return bool
     */
    public static function cekJadwal(int $userId): bool
    {
        $schedules = self::where('user_id', $userId)
            ->where('status', 'active')
            ->get();

        foreach ($schedules as $schedule) {
            if ($schedule->cekJadwalHarian()) {
                return true;
            }
        }

        return false;
    }


    /**
     * Ambil semua jadwal aktif hari ini (untuk admin)
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getJadwalHariIni()
    {
        $todayDate = Carbon::today()->toDateString();
        $todayDay  = strtolower(Carbon::today()->translatedFormat('l'));
        $dayOfMonth = Carbon::today()->day;

        // Ambil semua jadwal aktif
        $schedules = self::with('user')->where('status', 'active')->get();

        // filter hanya jadwal yang berlaku hari ini
        return $schedules->filter(function ($schedule) use ($todayDate, $todayDay, $dayOfMonth) {
            if ($schedule->start_date && $todayDate < $schedule->start_date) return false;
            if ($schedule->end_date && $todayDate > $schedule->end_date) return false;

            $rules = $schedule->rules ?? [];

            switch ($schedule->type) {
                case 'daily':
                    return true;
                case 'weekly':
                    return !empty($rules['weekly']) && in_array($todayDay, array_map('strtolower', $rules['weekly']));
                case 'monthly':
                    return !empty($rules['monthly']) && in_array($dayOfMonth, $rules['monthly']);
                case 'custom':
                    return !empty($rules['custom']) && in_array($todayDate, $rules['custom']);
                default:
                    return false;
            }
        })->values(); // reset key index
    }
}
