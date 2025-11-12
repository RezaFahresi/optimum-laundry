<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StokController extends Controller
{
    private const STOK_ROUTE = 'admin/stok'; // ✅ Hindari duplikasi string literal

    public function index()
    {
        $data['stok'] = DB::table('stok')->get();
        $data['user'] = User::find(Auth::id());

        return view('admin.stok', $data);
    }

    public function stoksimpan(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'stok' => 'required|integer|min:0',
        ]);

        DB::table('stok')->insert([
            'nama' => $request->nama,
            'stok' => $request->stok,
        ]);

        return redirect(self::STOK_ROUTE)->with('success', 'Data stok berhasil ditambahkan.');
    }

    public function stokedit($id)
    {
        $data['stok'] = DB::table('stok')->where('idstok', $id)->first();
        $data['user'] = User::find(Auth::id());

        if (!$data['stok']) {
            return redirect(self::STOK_ROUTE)->with('error', 'Data stok tidak ditemukan.');
        }

        return view('admin.stokedit', $data);
    }

    public function stokupdate(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'stok' => 'required|integer|min:0',
        ]);

        DB::table('stok')->where('idstok', $id)->update([
            'nama' => $request->nama,
            'stok' => $request->stok,
        ]);

        return redirect(self::STOK_ROUTE)->with('success', 'Data stok berhasil diperbarui.');
    }

    public function stokhapus($id)
    {
        DB::table('stok')->where('idstok', $id)->delete();
        return redirect(self::STOK_ROUTE)->with('success', 'Data stok berhasil dihapus.');
    }
}
