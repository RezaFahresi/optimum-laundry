<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Fotopengembalian;
use App\Models\Item;
use App\Models\PriceList;
use App\Models\Schedule;
use App\Models\Service;
use App\Models\ServiceType;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\TransactionLog;
use App\Models\User;
use App\Models\UserVoucher;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            abort(403);
        }

        $transactions = Transaction::with('status')
            ->where('member_id', $user->id)
            ->orderBy('created_at', 'DESC')
            ->orderBy('status_id', 'ASC')
            ->get();

        return view('member.transactions_history', compact('user', 'transactions'));
    }


    public function show(string|int $id)
    {
        $user = Auth::user();
        $transaction = Transaction::with(['service_type', 'status'])->findOrFail($id);

        $transactions = TransactionDetail::with([
            'price_list.item',
            'price_list.service',
            'price_list.category',
        ])->where('transaction_id', $id)->get();

        return view('member.detail', compact('user', 'transaction', 'transactions'));
    }

    public function complaint(Request $request, $id)
    {
        $request->validate([
            'keluhan' => 'required|string|max:500',
            'foto.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        DB::beginTransaction();

        try {
            // Ambil transaksi
            $transaction = Transaction::findOrFail($id);

            // Simpan log status lama
            $oldStatus = $transaction->status_id;

            // Update transaksi
            $transaction->update([
                'status_id' => 4, // id status 4 = "Komplain Pengembalian"
                'keluhan' => $request->keluhan,
            ]);

            // Simpan log perubahan status
            TransactionLog::create([
                'transaction_id' => $transaction->id,
                'changed_by' => Auth::id(),
                'old_status' => $oldStatus,
                'new_status' => 4,
                'note' => 'Pengajuan komplain pengembalian oleh member.',
            ]);

            // Simpan semua foto bukti
            if ($request->hasFile('foto')) {
                foreach ($request->file('foto') as $file) {
                    $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('uploads/pengembalian'), $filename);

                    Fotopengembalian::create([
                        'transaction_id' => $transaction->id,
                        'foto' => 'uploads/pengembalian/' . $filename,
                    ]);
                }
            }

            DB::commit();

            return back()->with('success', 'Pengembalian berhasil dikirim, admin akan segera memproses.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


    public function create()
    {
        $user = Auth::user();
        $items = Item::all();
        $services = Service::all();
        $categories = Category::all();
        $serviceTypes = ServiceType::all();

        // Jika session transaksi ada, arahkan user ke halaman tambah isi transaksi
        if (session()->has('transaction')) {
            return redirect('member/transactions-tambah');
        }

        // Ambil jadwal rutin user
        $schedules = Schedule::where('user_id', $user->id)
            ->where('status', '!=', 'cancelled')
            ->orderBy('created_at', 'desc')
            ->get();

        $vouchers = UserVoucher::where([
            'user_id' => $memberIdSessionTransaction ?? $user->id,
            'used' => 0,
        ])->get();

        return view('member.transaction_input', compact(
            'user',
            'items',
            'categories',
            'services',
            'vouchers',
            'schedules',
            'serviceTypes',
        ));
    }



    public function simpanJadwal(Request $request)
    {
        $request->validate([
            'type'            => 'required|in:daily,weekly,monthly,custom',
            'start_date'      => 'required|date',
            'end_date'        => 'nullable|date|after_or_equal:start_date',
            'time'            => 'required',
            'pickup_option'   => 'required|in:none,wa,app',
            'order_details'   => 'required|array|min:1',
            'service_type_id' => 'required|exists:service_types,id',
            'voucher_id'      => 'nullable|exists:vouchers,id',
        ]);

        $orderDetails = [];
        $grandTotal   = 0;

        // ambil harga service type (sama utk semua item)
        $serviceType = ServiceType::findOrFail($request->service_type_id);
        $price = $serviceType->cost;

        foreach ($request->order_details as $detail) {
            $item     = Item::find($detail['item_id']);
            $service  = Service::find($detail['service_id']);
            $category = Category::find($detail['category_id']);

            $qty      = (int) $detail['quantity'];
            $amount   = (int) $detail['subtotal'] / $qty;   // harga satuan (dari field subtotal hidden di form)
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

        // hitung diskon (kalau ada voucher)
        $discount = 0;
        if ($request->voucher_id) {
            $voucher = UserVoucher::findOrFail($request->voucher_id);
            $discount = min(($grandTotal * $voucher->discount_percentage / 100), $voucher->max_discount);
        }

        $finalTotal = $grandTotal - $discount;

        $schedule = Schedule::create([
            'user_id'         => Auth::id(),
            'type'            => $request->type,
            'start_date'      => $request->start_date,
            'end_date'        => $request->end_date,
            'time'            => $request->time,
            'pickup_option'   => $request->pickup_option,
            'order_details'   => $orderDetails, // JSON lengkap
            'rules'           => $request->rules ?? null,
            'status'          => 'active',
            'service_type_id' => $serviceType->id,
            'voucher_id'      => $request->voucher_id,
            'total_amount'    => $finalTotal,
        ]);


        return redirect()->back()->with('success', 'Jadwal berhasil ditambahkan.');
    }




    public function updateSchedule(Request $request, $id)
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




    public function tambah(Request $request)
    {
        $user = Auth::user();
        $items = Item::all();
        $categories = Category::all();
        $services = Service::all();
        $serviceTypes = ServiceType::all();

        $sessionTransaction = $request->session()->get('transaction', []);
        $memberIdSessionTransaction = $request->session()->get('memberIdTransaction');

        $totalPrice = 0;
        foreach ($sessionTransaction as $item) {
            $totalPrice += $item['subTotal'];
        }

        $vouchers = UserVoucher::where([
            'user_id' => $memberIdSessionTransaction ?? $user->id,
            'used' => 0,
        ])->get();

        // Ambil jadwal rutin user
        $schedules = Schedule::where('user_id', $user->id)
            ->where('status', '!=', 'cancelled')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('member.transaction_input', compact(
            'user',
            'items',
            'categories',
            'services',
            'schedules',
            'serviceTypes',
            'sessionTransaction',
            'memberIdSessionTransaction',
            'totalPrice',
            'vouchers'
        ));
    }

    public function tambahKeSession(Request $request)
    {
        $request->validate([
            'member-id' => 'required|numeric',
            'item' => 'required|exists:items,id',
            'service' => 'required|exists:services,id',
            'category' => 'required|exists:categories,id',
            'quantity' => 'required|numeric|min:1',
        ]);

        // Ambil data item, service, dan kategori
        $item = Item::findOrFail($request->item);
        $service = Service::findOrFail($request->service);
        $category = Category::findOrFail($request->category);

        $quantity = $request->quantity;

        // Misal kamu punya tabel harga khusus (Price) yang menyimpan harga berdasarkan kombinasi item, service, category
        $priceRecord = PriceList::where('item_id', $item->id)
            ->where('service_id', $service->id)
            ->where('category_id', $category->id)
            ->first();

        if ($priceRecord) {
            $price = $priceRecord->price;
        } else {
            // Jika tidak ada harga khusus, fallback harga service saja (atau 0)
            $price = $service->price ?? 0;
        }

        $subTotal = $price * $quantity;

        // Siapkan data transaksi
        $data = [
            'itemId' => $item->id,
            'itemName' => $item->name,
            'serviceId' => $service->id,
            'serviceName' => $service->name,
            'categoryId' => $category->id,
            'categoryName' => $category->name,
            'quantity' => $quantity,
            'price' => $price,
            'subTotal' => $subTotal,
            'pickup_option' => $request->pickup_option
        ];

        // Ambil transaksi lama dari session
        $transactions = session()->get('transaction', []);
        $transactions[] = $data;

        // Simpan ke session
        session([
            'transaction' => $transactions,
            'memberIdTransaction' => $request->input('member-id'),
        ]);

        return redirect('member/transactions-tambah')->with('success', 'Pesanan berhasil ditambahkan ke daftar transaksi.');
    }


    public function hapusSession($index)
    {
        $transactions = session()->get('transaction', []); // sama dengan key yang di tambahKeSession

        if (isset($transactions[$index])) {
            unset($transactions[$index]);
            $transactions = array_values($transactions);
            session()->put('transaction', $transactions); // pastikan sama juga di sini
        }

        return redirect()->back()->with('success', 'Item berhasil dihapus dari transaksi.');
    }




    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'item' => 'required|exists:items,id',
    //         'service' => 'required|exists:services,id',
    //         'category' => 'required|exists:categories,id',
    //         'quantity' => 'required|numeric|min:1',
    //     ]);

    //     $user = Auth::user();

    //     $transaction = Transaction::create([
    //         'member_id' => $user->id,
    //         'admin_id' => 1,
    //         'discount' => 0,
    //         'status_id' => 1,
    //         'total_price' => 0, // akan diupdate nanti jika perlu
    //     ]);

    //     TransactionDetail::create([
    //         'transaction_id' => $transaction->id,
    //         'item_id' => $request->item,
    //         'service_id' => $request->service,
    //         'category_id' => $request->category,
    //         'quantity' => $request->quantity,
    //     ]);

    //     return redirect('member.transactions.index')->with('success', 'Pesanan berhasil dibuat!');
    // }

    public function store(Request $request)
    {
        $request->validate([
            'payment-amount' => ['required', 'integer'],
            'upload_bukti' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ]);

        DB::beginTransaction();

        try {
            $memberId = $request->session()->get('memberIdTransaction');
            $user = User::where('role', 1)->firstOrFail();

            if (!$user || !$memberId) {
                abort(403);
            }

            $adminId = $user->id;
            $sessionTransaction = $request->session()->get('transaction');

            if (!$sessionTransaction || count($sessionTransaction) === 0) {
                return redirect()->back()->with('error', 'Tidak ada item dalam transaksi.');
            }

            // Hitung total harga dari detail item
            $totalPrice = 0;
            foreach ($sessionTransaction as $trs) {
                $totalPrice += $trs['subTotal'];
            }

            $discount = 0;
            $voucherId = $request->input('voucher');
            $serviceTypeId = $request->input('service_type_id') ?? 0;

            $cost = 0;

            // Jika ada voucher digunakan
            if (!empty($voucherId) && $voucherId != 0) {
                $userVoucher = UserVoucher::findOrFail($voucherId);

                if (!$userVoucher->voucher || $userVoucher->used) {
                    abort(400, 'Voucher tidak valid atau sudah digunakan.');
                }

                $discount = $userVoucher->voucher->discount_value;
                $totalPrice -= $discount;
                if ($totalPrice < 0) {
                    $totalPrice = 0;
                }

                // Tandai sebagai digunakan
                $userVoucher->used = true;
                $userVoucher->save();
            }

            // Jika ada service type non-reguler
            if (!empty($serviceTypeId) && $serviceTypeId != 0) {
                $serviceType = ServiceType::findOrFail($serviceTypeId);
                $cost = $serviceType->cost;
                $totalPrice += $cost;
            }

            // Cek pembayaran cukup
            $paymentAmount = $request->input('payment-amount');
            if ($paymentAmount < $totalPrice) {
                return redirect('member/transactions-create')
                    ->with('error', 'Pembayaran kurang');
            }

            // Upload bukti jika ada
            $buktiPath = null;
            if ($request->hasFile('upload_bukti')) {
                $file = $request->file('upload_bukti');
                $filename = time() . '_' . $file->getClientOriginalName();
                $destinationPath = public_path('uploads');
                $file->move($destinationPath, $filename);
                $buktiPath = 'uploads/' . $filename; // path relatif untuk disimpan ke database
            }


            // Simpan transaksi
            $transaction = Transaction::create([
                'status_id'       => 1,
                'member_id'       => $memberId,
                'admin_id'        => $adminId,
                'discount'        => $discount,
                'total'           => $totalPrice,
                'service_type_id' => $serviceTypeId,
                'service_cost'    => $cost,
                'payment_amount'  => $paymentAmount,
                'bukti_pembayaran' => $buktiPath,
                'metodepembayaran' => 'transfer',
                'pickup_option'    => $sessionTransaction[0]['pickup_option']
            ]);

            // Simpan detail
            foreach ($sessionTransaction as $trs) {
                $priceList = PriceList::where([
                    'item_id'     => $trs['itemId'],
                    'category_id' => $trs['categoryId'],
                    'service_id'  => $trs['serviceId'],
                ])->firstOrFail();

                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'price_list_id'  => $priceList->id,
                    'quantity'       => $trs['quantity'],
                    'price'          => $priceList->price,
                    'sub_total'      => $trs['subTotal'],
                ]);
            }

            DB::table('stok')->decrement('stok', 1);

            // Tambah poin user
            $targetUser = User::findOrFail($memberId);
            $targetUser->increment('point');

            // Hapus session
            $request->session()->forget('transaction');
            $request->session()->forget('memberIdTransaction');

            DB::commit();

            if ($sessionTransaction[0]['pickup_option'] != 'none') {
                return redirect('member/transactions-create')
                    ->with('success', 'Terimakasih telah melakukan transaksi. Petugas akan segera menuju lokasi kamu. Silahkan siapkan cucian laundry untuk proses pengambilan')
                    ->with('id_trs', $transaction->id);
            } else {
                return redirect('member/transactions-create')
                    ->with('success', 'Transaksi berhasil disimpan')
                    ->with('id_trs', $transaction->id);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


    public function hapusSchedule($id)
    {
        $schedule = Schedule::findOrFail($id);

        try {
            $schedule->delete();
            return redirect()->back()->with('success', 'Jadwal berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus jadwal: ' . $e->getMessage());
        }
    }
}
