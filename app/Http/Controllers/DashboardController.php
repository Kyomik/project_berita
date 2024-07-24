<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\KomentarBerita;
use App\Models\Detail;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Mendapatkan id_user dari session
        $id_user = $request->attributes->get('user')->id_user;
        

        // if ($id_user['hak_akses'] !== 'admin') {
        //     return response()->json(['error' => 'Unauthorized'], 401);
        // }

        $totalBerita = Berita::whereHas('detail', function ($query) use ($id_user) {
            $query->where('id_user', $id_user);
        })->count();

        $totalKomentar = KomentarBerita::whereHas('berita', function ($query) use ($id_user) {
            $query->whereHas('detail', function ($query) use ($id_user) {
                $query->where('id_user', $id_user);
            });
        })->count();

        $totalViews = Detail::where('id_user', $id_user)->sum('views');

        return view('/admin/home', compact('totalBerita', 'totalKomentar', 'totalViews'));
    }
}



