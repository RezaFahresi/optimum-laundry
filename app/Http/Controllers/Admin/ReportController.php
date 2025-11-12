<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Carbon\Carbon;
use DateTime;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use PDF;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportController extends Controller
{
    /**
     * Show report page
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index(): View
    {
        $user = Auth::user();

        $years = DB::table('transactions')
            ->selectRaw('YEAR(created_at) as Tahun')
            ->union(
                DB::table('pengeluaran')->selectRaw('YEAR(tanggal) as Tahun')
            )
            ->orderBy('Tahun', 'desc')
            ->distinct()
            ->get();

        return view('admin.report', compact('user', 'years'));
    }

    public function chartData(Request $request): JsonResponse
    {
        $year = $request->input('year', Carbon::now()->year);

        $income = DB::table('transactions')
            ->selectRaw('MONTH(created_at) as month, SUM(total) as total')
            ->whereYear('created_at', $year)
            ->groupByRaw('MONTH(created_at)')
            ->orderByRaw('MONTH(created_at)')
            ->get();

        $expense = DB::table('pengeluaran')
            ->selectRaw('MONTH(tanggal) as month, SUM(jumlah) as total')
            ->whereYear('tanggal', $year)
            ->groupByRaw('MONTH(tanggal)')
            ->orderByRaw('MONTH(tanggal)')
            ->get();

        $incomeData = array_fill(1, 12, 0);
        $expenseData = array_fill(1, 12, 0);

        foreach ($income as $row) {
            $incomeData[$row->month] = (float) $row->total;
        }
        foreach ($expense as $row) {
            $expenseData[$row->month] = (float) $row->total;
        }

        return response()->json([
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
            'income' => array_values($incomeData),
            'expense' => array_values($expenseData),
            'year' => $year
        ]);
    }

    /**
     * Print report as pdf
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    // public function print(Request $request): Response
    // {
    //     $yearInput = $request->input('year');
    //     $monthInput = $request->input('month');
    //     $dateInput = $request->input('date'); // tanggal harian optional

    //     if ($dateInput) {
    //         // Jika tanggal spesifik diisi, filter berdasarkan tanggal tersebut (YYYY-MM-DD)
    //         $date = Carbon::parse($dateInput)->format('Y-m-d');

    //         $revenue = Transaction::whereDate('created_at', $date)->sum('total');
    //         $transactionsCount = Transaction::whereDate('created_at', $date)->count();

    //         $reportTitle = "Laporan Keuangan Harian: " . Carbon::parse($date)->translatedFormat('d F Y');
    //     } else {
    //         // Jika tidak ada tanggal, pakai bulan dan tahun
    //         $dateObj = DateTime::createFromFormat('!m', $monthInput);
    //         if (!$dateObj) {
    //             abort(500, 'Bulan tidak valid');
    //         }
    //         $monthName = $dateObj->format('F');

    //         $revenue = Transaction::whereYear('created_at', $yearInput)
    //             ->whereMonth('created_at', $monthInput)
    //             ->sum('total');

    //         $transactionsCount = Transaction::whereYear('created_at', $yearInput)
    //             ->whereMonth('created_at', $monthInput)
    //             ->count();

    //         $reportTitle = "Laporan Keuangan Bulanan: $monthName $yearInput";
    //     }

    //     $pdf = PDF::loadView(
    //         'admin.report_pdf',
    //         compact(
    //             'revenue',
    //             'transactionsCount',
    //             'reportTitle',
    //             'yearInput',
    //             'monthInput',
    //             'dateInput',
    //         )
    //     );

    //     return $pdf->stream();
    // }

    public function print(Request $request)
    {
        $yearInput = $request->input('year');
        $monthInput = $request->input('month');
        $dateInput = $request->input('date');
        $action = $request->input('action');

        // Validasi minimal input tanggal atau bulan & tahun
        if (!$dateInput && (!$monthInput || !$yearInput)) {
            return back()->with('error', 'Silakan pilih tanggal atau bulan dan tahun terlebih dahulu');
        }

        // === Filter tanggal ===
        if ($dateInput) {
            $date = Carbon::parse($dateInput)->format('Y-m-d');

            $transactions = \App\Models\Transaction::with([
                'transaction_details.price_list.item',
                'transaction_details.price_list.category',
                'transaction_details.price_list.service',
                'status',
                'service_type'
            ])->whereDate('created_at', $date)->get();

            $pengeluaran = DB::table('pengeluaran')->whereDate('tanggal', $date)->get();

            $reportTitle = "Laporan Harian: " . Carbon::parse($date)->translatedFormat('d F Y');
        } else {
            $transactions = \App\Models\Transaction::with([
                'transaction_details.price_list.item',
                'transaction_details.price_list.category',
                'transaction_details.price_list.service',
                'status',
                'service_type'
            ])->whereYear('created_at', $yearInput)
                ->whereMonth('created_at', $monthInput)
                ->get();

            $pengeluaran = DB::table('pengeluaran')
                ->whereYear('tanggal', $yearInput)
                ->whereMonth('tanggal', $monthInput)
                ->get();

            $monthName = Carbon::create()->month($monthInput)->translatedFormat('F');
            $reportTitle = "Laporan Bulanan: $monthName $yearInput";
        }

        // === Hitung total ===
        $revenue = $transactions->sum('total');
        $expense = $pengeluaran->sum('jumlah');
        $profit = $revenue - $expense;

        $transactionsCount = $transactions->count();
        $pengeluaranCount = $pengeluaran->count();

        // === PDF Export ===
        if ($action === 'pdf') {
            $pdf = PDF::loadView('admin.report_pdf', compact(
                'transactions',
                'pengeluaran',
                'revenue',
                'expense',
                'profit',
                'transactionsCount',
                'pengeluaranCount',
                'reportTitle',
                'yearInput',
                'monthInput',
                'dateInput'
            ))->setPaper('A4', 'portrait');

            return $pdf->stream('laporan_keuangan.pdf');
        }

        // === Excel Export ===
        if ($action === 'excel') {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Header untuk transaksi
            $sheet->setCellValue('A1', '=== LAPORAN TRANSAKSI ===');
            $sheet->fromArray(['ID Transaksi', 'Tanggal', 'Total', 'Detail (Item x Qty = Subtotal)'], NULL, 'A2');

            $row = 3;
            foreach ($transactions as $trx) {
                $details = $trx->transaction_details->map(function ($d) {
                    $item = $d->price_list->item->name ?? 'Item';
                    $category = $d->price_list->category->name ?? 'Kategori';
                    $service = $d->price_list->service->name ?? 'Layanan';
                    return "$item ($category/$service) x {$d->quantity} = {$d->sub_total}";
                })->implode(', ');

                $sheet->fromArray([
                    $trx->id,
                    $trx->created_at->format('Y-m-d'),
                    $trx->total,
                    $details
                ], NULL, "A$row");
                $row++;
            }

            // Spacer
            $row += 2;

            // Header pengeluaran
            $sheet->setCellValue("A$row", '=== LAPORAN PENGELUARAN ===');
            $row++;
            $sheet->fromArray(['ID', 'Tanggal', 'Judul', 'Jumlah', 'Deskripsi'], NULL, "A$row");
            $row++;

            foreach ($pengeluaran as $p) {
                $sheet->fromArray([
                    $p->idpengeluaran,
                    Carbon::parse($p->tanggal)->format('Y-m-d'),
                    $p->judul,
                    $p->jumlah,
                    $p->deskripsi,
                ], NULL, "A$row");
                $row++;
            }

            // Spacer
            $row += 2;

            // Total ringkasan
            $sheet->setCellValue("A$row", "Total Pendapatan: Rp " . number_format($revenue, 0, ',', '.'));
            $row++;
            $sheet->setCellValue("A$row", "Total Pengeluaran: Rp " . number_format($expense, 0, ',', '.'));
            $row++;
            $sheet->setCellValue("A$row", "Laba/Rugi: Rp " . number_format($profit, 0, ',', '.'));

            $filename = 'laporan_keuangan.xlsx';
            $writer = new Xlsx($spreadsheet);
            $temp_file = tempnam(sys_get_temp_dir(), $filename);
            $writer->save($temp_file);

            return Response::download($temp_file, $filename)->deleteFileAfterSend(true);
        }

        return back()->with('error', 'Format laporan tidak valid');
    }


    /**
     * Get month by year report
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMonth(Request $request): JsonResponse
    {
        $year = $request->input('year', now()->year);
        $month = Transaction::whereYear('created_at', $year)
            ->selectRaw('MONTH(created_at) as Bulan')
            ->distinct()
            ->get();

        return response()->json($month);
    }
}
