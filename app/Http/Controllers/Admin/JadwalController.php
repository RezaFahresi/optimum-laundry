<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use App\Models\Service;
use App\Models\ServiceType;
use App\Models\PriceList;
use App\Models\UserVoucher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Carbon\Carbon;

class JadwalController extends Controller
{
    // public function index()
    // {
    //     // pastikan admin
    //     $user = Auth::user();
    //     if (!$user) {
    //         abort(403);
    //     }

    //     $items = Item::all();
    //     $categories = Category::all();
    //     $services = Service::all();
    //     $serviceTypes = ServiceType::all();

    //     // ambil semua schedule dengan user
    //     $schedules = Schedule::with('user')
    //         ->orderBy('type', 'ASC')
    //         ->orderBy('start_date', 'DESC')
    //         ->get();

    //     // hitung total per tipe
    //     $totals = [
    //         'daily'   => $schedules->where('type', 'daily')->count(),
    //         'weekly'  => $schedules->where('type', 'weekly')->count(),
    //         'monthly' => $schedules->where('type', 'monthly')->count(),
    //         'custom'  => $schedules->where('type', 'custom')->count(),
    //     ];

    //     return view('admin.jadwal', compact('schedules', 'totals', 'user', 'items', 'categories', 'services', 'serviceTypes'));
    // }

    public function index(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            abort(403);
        }

        $query = Schedule::with('user');

        // Filter Hari (khusus type=weekly)
        if ($request->filled('hari')) {
            $hari = strtolower($request->hari);

            $query->where(function ($q) use ($hari) {
                // daily → selalu tampil
                $q->where('type', 'daily')

                    // weekly → cek apakah hari ini ada di rules.weekly
                    ->orWhere(function ($sub) use ($hari) {
                        $sub->where('type', 'weekly')
                            ->where(function ($inner) use ($hari) {
                                $inner->whereRaw("JSON_EXTRACT(rules, '$.weekly') LIKE ?", ['%"' . $hari . '"%']);
                            });
                    })

                    // monthly → kalau user pilih angka (misal "15"), cari di rules.monthly
                    ->orWhere(function ($sub) use ($hari) {
                        if (is_numeric($hari)) { 
                            $sub->where('type', 'monthly')
                                ->whereRaw("JSON_EXTRACT(rules, '$.monthly') LIKE ?", ['%"' . $hari . '"%']);
                        }
                    })

                    // custom → kalau user pilih tanggal (misal "2025-10-20"), cari di rules.custom
                    ->orWhere(function ($sub) use ($hari) {
                        if (preg_match('/\d{4}-\d{2}-\d{2}/', $hari)) {
                            $sub->where('type', 'custom')
                                ->whereRaw("JSON_EXTRACT(rules, '$.custom') LIKE ?", ['%"' . $hari . '"%']);
                        }
                    });
            });
        }

        // Filter Nama Customer
        if ($request->filled('nama')) {
            $nama = $request->nama;
            $query->whereHas('user', function ($q) use ($nama) {
                $q->where('name', 'LIKE', "%$nama%");
            });
        }

        // Filter Wilayah
        if ($request->filled('wilayah')) {
            $wilayah = $request->wilayah;
            $query->whereHas('user', function ($q) use ($wilayah) {
                $q->where('address', 'LIKE', "%$wilayah%");
            });
        }

        $schedules = $query->orderBy('type', 'ASC')
            ->orderBy('start_date', 'DESC')
            ->get();

        $items = Item::all();
        $categories = Category::all();
        $services = Service::all();
        $serviceTypes = ServiceType::all();

        $totals = [
            'daily'   => $schedules->where('type', 'daily')->count(),
            'weekly'  => $schedules->where('type', 'weekly')->count(),
            'monthly' => $schedules->where('type', 'monthly')->count(),
            'custom'  => $schedules->where('type', 'custom')->count(),
        ];

        /* ===================================================
       🔔 CEK NOTIFIKASI JADWAL HARI INI (semua type)
       =================================================== */
        $today      = strtolower(Carbon::now()->locale('id')->dayName); // contoh: senin
        $todayDate  = Carbon::now()->format('d'); // contoh: 20
        $todayFull  = Carbon::now()->format('Y-m-d'); // contoh: 2025-10-20

        // Ambil semua schedule aktif
        $allSchedules = Schedule::with('user')->get();

        // Filter manual di PHP Collection
        $todaySchedules = $allSchedules->filter(function ($sch) use ($today, $todayDate, $todayFull) {
            $rules = is_string($sch->rules) ? json_decode($sch->rules, true) : $sch->rules;
            if (!is_array($rules)) return false;

            switch ($sch->type) {
                case 'daily':
                    return true; // tampil tiap hari

                case 'weekly':
                    $weekly = $rules['weekly'] ?? [];
                    return in_array($today, $weekly);

                case 'monthly':
                    $monthly = $rules['monthly'] ?? [];
                    return in_array($todayDate, $monthly);

                case 'custom':
                    $custom = $rules['custom'] ?? [];
                    return in_array($todayFull, $custom);

                default:
                    return false;
            }
        });

        // Siapkan data notifikasi
        $notifications = [];

        foreach ($todaySchedules as $sch) {
            $orderDetails = is_string($sch->order_details)
                ? json_decode($sch->order_details, true)
                : $sch->order_details;

            $details = collect($orderDetails)->map(function ($d) {
                return "{$d['item_name']} ({$d['category_name']}/{$d['service_name']}) x{$d['quantity']}";
            })->implode(', ');

            $notifications[] = [
                'user'     => $sch->user->name ?? 'Tanpa Nama',
                'address'  => $sch->user->address ?? '-',
                'type'     => ucfirst($sch->type),
                'time'     => $sch->time ?? '-',
                'details'  => $details,
                'total'    => number_format($sch->total_amount, 0, ',', '.'),
            ];
        }

        return view('admin.jadwal', compact(
            'schedules',
            'totals',
            'user',
            'items',
            'categories',
            'services',
            'serviceTypes',
            'notifications'
        ));
    }



    public function bayar($id)
    {
        $schedule = Schedule::with(['user', 'serviceType'])->findOrFail($id);
        $today = now()->format('Y-m-d');

        $existingTransaction = Transaction::where('member_id', $schedule->user_id)
            ->whereDate('created_at', $today)
            ->where('service_type_id', $schedule->service_type_id)
            ->first();

        if ($existingTransaction) {
            return redirect()->back()->with('info', 'Transaksi untuk jadwal ini hari ini sudah dibuat.');
        }

        $orderDetails = is_array($schedule->order_details)
            ? $schedule->order_details
            : json_decode($schedule->order_details ?? '[]', true);

        DB::beginTransaction();
        try {
            $transactionId = DB::table('transactions')->insertGetId([
                'status_id' => 3,
                'service_type_id' => $schedule->service_type_id,
                'admin_id' => Auth::id(),
                'member_id' => $schedule->user_id,
                'service_cost' => $schedule->serviceType->cost ?? 0,
                'discount' => 0,
                'total' => $schedule->total_amount,
                'payment_amount' => $schedule->total_amount,
                'metodepembayaran' => 'cash',
                'finish_date' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($orderDetails as $detail) {
                $priceList = DB::table('price_lists')
                    ->where('item_id', $detail['item_id'])
                    ->where('category_id', $detail['category_id'])
                    ->where('service_id', $detail['service_id'])
                    ->first();

                if ($priceList) {
                    DB::table('transaction_details')->insert([
                        'transaction_id' => $transactionId,
                        'price_list_id' => $priceList->id,
                        'quantity' => $detail['quantity'] ?? 0,
                        'price' => $detail['price'] ?? 0,
                        'sub_total' => $detail['subtotal'] ?? 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            DB::table('transaction_logs')->insert([
                'transaction_id' => $transactionId,
                'changed_by' => Auth::id(),
                'old_status' => null,
                'new_status' => 'Selesai',
                'note' => 'Transaksi otomatis dibuat dari jadwal rutin.',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Transaksi berhasil dibuat dan dicatat ke log.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal membuat transaksi: ' . $e->getMessage());
        }
    }



    public function getPrice(Request $request)
    {
        $itemId = $request->item_id;
        $serviceId = $request->service_id;
        $categoryId = $request->category_id;

        $priceRecord = PriceList::where('item_id', $itemId)
            ->where('service_id', $serviceId)
            ->where('category_id', $categoryId)
            ->first();

        $price = $priceRecord ? $priceRecord->price : 0;

        return response()->json(['price' => $price]);
    }



    public function updateJadwal(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'type'            => 'required|in:daily,weekly,monthly,custom',
            'start_date'      => 'required|date',
            'end_date'        => 'nullable|date|after_or_equal:start_date',
            'time'            => 'required',
            'pickup_option'   => 'required|in:none,wa,app',
            'order_details'   => 'required|array|min:1',
            'order_details.*.item_id' => 'required|exists:items,id',
            'order_details.*.service_id' => 'required|exists:services,id',
            'order_details.*.category_id' => 'required|exists:categories,id',
            'order_details.*.quantity' => 'required|integer|min:1',
            'order_details.*.subtotal' => 'required|numeric|min:0',
            'service_type_id' => 'required|exists:service_types,id',
            'voucher_id'      => 'nullable|exists:vouchers,id',
            'rules'           => 'nullable|array',
        ]);

        // Ambil jadwal lama
        $schedule = Schedule::findOrFail($id);

        // Siapkan order details baru dan hitung grand total
        $orderDetails = [];
        $grandTotal = 0;

        $serviceType = ServiceType::findOrFail($request->service_type_id);
        $price = $serviceType->cost;

        foreach ($request->order_details as $detail) {
            $item     = Item::findOrFail($detail['item_id']);
            $service  = Service::findOrFail($detail['service_id']);
            $category = Category::findOrFail($detail['category_id']);

            $qty      = (int) $detail['quantity'];
            $amount   = (int) $detail['subtotal'] / $qty;
            $subtotal = $amount * $qty;
            $total    = $subtotal + $price;

            $grandTotal += $total;

            $orderDetails[] = [
                'item_id'       => $item->id,
                'item_name'     => $item->name,
                'service_id'    => $service->id,
                'service_name'  => $service->name,
                'category_id'   => $category->id,
                'category_name' => $category->name,
                'quantity'      => $qty,
                'amount'        => $amount,
                'price'         => $price,
                'subtotal'      => $subtotal,
                'total'         => $total,
            ];
        }

        // Hitung diskon jika ada voucher
        $discount = 0;
        if ($request->voucher_id) {
            $voucher = UserVoucher::findOrFail($request->voucher_id);
            $discount = min(($grandTotal * $voucher->discount_percentage / 100), $voucher->max_discount);
        }

        $finalTotal = $grandTotal - $discount;

        // Buat rules baru, hapus data lama sepenuhnya
        $rules = [
            'weekly'  => $request->input('rules.weekly', []),
            'monthly' => $request->input('rules.monthly', []),
            'custom'  => $request->input('rules.custom', []),
        ];

        // Update jadwal
        $schedule->update([
            'type'            => $request->type,
            'start_date'      => $request->start_date,
            'end_date'        => $request->end_date,
            'time'            => $request->time,
            'pickup_option'   => $request->pickup_option,
            'order_details'   => $orderDetails,
            'rules'           => $rules, // overwrite semua rules lama
            'status'          => $request->status ?? 'active',
            'service_type_id' => $serviceType->id,
            'voucher_id'      => $request->voucher_id,
            'total_amount'    => $finalTotal,
        ]);

        return redirect()->back()->with('success', 'Jadwal berhasil diperbarui.');
    }




    public function hapusJadwal($id)
    {
        $schedule = Schedule::findOrFail($id);

        try {
            $schedule->delete();
            return redirect()->back()->with('success', 'Jadwal berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus jadwal: ' . $e->getMessage());
        }
    }





    public function prosesJadwal($id)
    {
        $schedule = Schedule::findOrFail($id);

        if (empty($schedule->order_details) || count($schedule->order_details) === 0) {
            return redirect()->back()->with('error', 'Jadwal ini tidak memiliki order detail.');
        }

        DB::beginTransaction();
        try {
            // Hitung total dari order_details
            $totalPrice = collect($schedule->order_details)->sum('subtotal');

            $adminId = 1; // admin default
            $memberId = $schedule->user_id;
            $serviceTypeId = $schedule->service_type_id ?? null;
            $serviceCost = $schedule->serviceType ? $schedule->serviceType->cost : 0;

            // Tambahkan service cost jika ada
            $totalPrice += $serviceCost;

            // Simpan transaksi
            $transaction = Transaction::create([
                'status_id'       => 1, // misal status baru
                'member_id'       => $memberId,
                'admin_id'        => $adminId,
                'discount'        => 0,
                'total'           => $totalPrice,
                'service_type_id' => $serviceTypeId,
                'service_cost'    => $serviceCost,
                'payment_amount'  => $totalPrice, // asumsi dibayar penuh otomatis
                'bukti_pembayaran' => null,
                'metodepembayaran' => 'transfer',
            ]);

            // Simpan detail transaksi
            foreach ($schedule->order_details as $detail) {
                $priceList = PriceList::where([
                    'item_id'     => $detail['item_id'],
                    'category_id' => $detail['category_id'],
                    'service_id'  => $detail['service_id'],
                ])->first();

                if (!$priceList) {
                    DB::rollBack();
                    return redirect()->back()->with('error', "PriceList tidak ditemukan untuk item_id {$detail['item_id']}");
                }

                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'price_list_id'  => $priceList->id,
                    'quantity'       => $detail['quantity'],
                    'price'          => $detail['price'],
                    'sub_total'      => $detail['subtotal'],
                ]);
            }

            // Update last_proccess
            $schedule->last_proccess = Carbon::today()->toDateString();
            $schedule->save();

            DB::commit();

            return redirect()->back()->with('success', "Jadwal berhasil diproses, transaksi #{$transaction->id} dibuat.");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', "Gagal memproses jadwal: " . $e->getMessage());
        }
    }
}
