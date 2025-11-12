<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PengeluaranController extends Controller
{
    public function index()
    {
        $data['pengeluaran'] = DB::table('pengeluaran')->orderBy('idpengeluaran', 'DESC')->get();
        $data['user'] = User::where('id', Auth::user()->id)->first();

        return view('admin.pengeluaran', $data);
    }

    public function pengeluaransimpan(Request $request)
    {
        DB::table('pengeluaran')->insert([
            'judul' => $request->judul,
            'jumlah' => $request->jumlah,
            'tanggal' => $request->tanggal,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->back()->with('success', 'Pengeluaran berhasil ditambahkan.');
    }

    public function pengeluaranedit($id)
    {
        $data['pengeluaran'] = DB::table('pengeluaran')->where('idpengeluaran', $id)->first();
        $data['user'] = User::where('id', Auth::user()->id)->first();

        if (!$data['pengeluaran']) {
            return redirect('admin/pengeluaran')->with('error', 'Data pengeluaran tidak ditemukan.');
        }

        return view('admin.pengeluaranedit', $data);
    }

    public function pengeluaranupdate(Request $request, $id)
    {
        DB::table('pengeluaran')->where('idpengeluaran', $id)->update([
            'judul' => $request->judul,
            'jumlah' => $request->jumlah,
            'tanggal' => $request->tanggal,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect('admin/pengeluaran')->with('success', 'Pengeluaran berhasil diperbarui.');
    }

    public function pengeluaranhapus($id)
    {
        DB::table('pengeluaran')->where('idpengeluaran', $id)->delete();
        return redirect()->back()->with('success', 'Pengeluaran berhasil dihapus.');
    }
}
