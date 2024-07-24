<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Login;
use App\Models\Detail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;

class AdminController extends Controller
{   
    public function index(Request $request)
    {
        $perPage = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $offset = ($currentPage - 1) * $perPage;
    
        // Ambil semua admin dan user_admin
        $users = User::with('login')
            ->whereIn('hak_akses', ['admin', 'user_admin'])
            ->get();
    
        // Transform data
        $transformedUsers = $users->map(function ($admin) {
            $jumlah_berita = $admin->hak_akses === 'admin' ? Detail::where('id_user', $admin->id_user)->count() : 0;
    
            return [
                'id_user' => $admin->id_user,
                'nama_user' => $admin->nama_user,
                'jumlah_berita' => $jumlah_berita,
                'email' => $admin->login ? $admin->login->username : null,
                'password' => $admin->login ? $admin->login->password : null,
                'hak_akses' => $admin->hak_akses,
            ];
        });
    
        // Total items
        $total = $transformedUsers->count();
    
        // Ambil data yang dibutuhkan untuk halaman saat ini
        $currentPageItems = $transformedUsers->slice($offset, $perPage)->all();
    
        // Buat LengthAwarePaginator
        $admins = new LengthAwarePaginator($currentPageItems, $total, $perPage, $currentPage, [
            'path' => $request->url(),
            'pageName' => 'page',
        ]);
    
        return view('admin.super-admin.akun.index', compact('admins'));
    }
    

    public function showAddForm()
    {
        return view('admin.super-admin.akun.tambah');
    }
    // public function __construct()
    // {
    //     $this->middleware('auth:api');
    //     $this->middleware('is_super_admin');
    // }


    public function addAdmin(Request $request)
    {
        // Validasi input
    
        $validator = Validator::make($request->all(), [
            'nama_user' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:logins',
            'password' => 'required|string|min:6',
            'hak_akses' => 'required|in:admin,user_admin',
        ]);
        
        
        // if ($validator->fails()) {
        //     return redirect()->back()->withErrors($validator)->withInput();
        // }
        // return response()->json($request->all());
        $user = User::create([
            'nama_user' => $request->nama_user,
            'hak_akses' => $request->hak_akses
        ]);

        Login::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'id_user' => $user->id_user
        ]);

        
        return response()->json(['redirect' => '../../admin/index']);
    }


    public function edit($id)
    {
        $admin = User::findOrFail($id);
        return view('admin.super-admin.akun.edit', compact('admin'));
    }


    public function update(Request $request, $id)
    {
        $admin = User::findOrFail($id);
        $login = $admin->login;

        $validator = Validator::make($request->all(), [
            'nama_user' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:logins,username,' . $login->username . ',username',
            'password' => 'nullable|string|min:8',
            'hak_akses' => 'required|in:admin,user_admin',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $admin->update([
            'nama_user' => $request->nama_user,
            'hak_akses' => $request->hak_akses,
            'password' => Hash::make($request->password),
        ]);
        

        $login->update([
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['redirect' => '../../admin/index']);
    }

    public function destroy($id)
    {
        $admin = User::findOrFail($id);
        $admin->login->delete();
        $admin->delete();

        return response()->json(['redirect' => '../../admin/index']);
    }
}
