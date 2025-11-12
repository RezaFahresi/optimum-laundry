<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {
        $data['users'] = User::whereIn('role', [3, 4])->get();

        $data['user'] = User::where('id', Auth::user()->id)->first();

        // $user = Auth::user();


        // return response()->json($data);
        return view('admin.user', $data);
    }

    public function usersimpan(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'role' => 'required',
        ]);

        DB::table('users')->insert([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
        ]);

        return back()->with('success', 'Data berhasil ditambahkan');
    }

    public function useredit($id)
    {
        $user = User::findOrFail($id);

        return view('admin.useredit', compact('user'));
    }

    // Update user - proses simpan perubahan
    public function userupdate(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|min:6',
            'role' => 'required',
        ]);

        $user = User::findOrFail($id);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;

        // Jika password diisi, hash dan update
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return redirect('admin/user')->with('success', 'Data berhasil diupdate');
    }

    // Hapus user
    public function userdestroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return back()->with('success', 'Data berhasil dihapus');
    }
}
