<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\Detail;
use App\Models\Paragraft;
use Illuminate\Http\Request;
use App\Models\KategoriBerita;
use App\Models\KomentarBerita;
use Illuminate\Support\Facades\DB;

class KategoriController extends Controller
{
    public function index(){
        $kategori = KategoriBerita::all();
        
        return view('admin.super-admin.kategori.manage', compact('kategori'));
    }
    public function manage(Request $request)
    {
        // Validasi user memiliki hak akses super_admin
        // $user = Auth::user(); // Misalnya menggunakan Laravel Auth untuk mendapatkan user saat ini
        // dd($request->all());
        $user = [
            'hak_akses' => 'super_admin'
        ];
        if ($user['hak_akses'] !== 'super_admin') {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Ambil data dari request
        $categories = $request->input('categories', []);
        $deletedCategories = $request->input('deletedCategories', []);

        try {
            // Mulai transaksi database
            DB::beginTransaction();

            // Hapus kategori yang telah dipilih untuk dihapus
            if (!empty($deletedCategories)) {
                foreach ($deletedCategories as $kategoriId) {
                    $existingCategory = KategoriBerita::find($kategoriId);

                    if($existingCategory){
                        $this->deleteCategory($kategoriId);
                    }
                }
            }

            // Tambah atau edit kategori
            if (!empty($categories)) {
                foreach ($categories as $categoryData) {
                    if (isset($categoryData['id_kategori'])) {
                        // Cek apakah id_kategori sudah ada di database
                        $existingCategory = KategoriBerita::find($categoryData['id_kategori']);

                        if ($existingCategory) {
                            // Jika ada id_kategori, lakukan update
                            $this->updateCategory($categoryData['id_kategori'], $categoryData['nama_kategori']);
                        } else {
                            // Jika tidak ada id_kategori, tambahkan kategori baru
                            $kategori = new KategoriBerita();
                            $kategori->nama_kategori = $categoryData['nama_kategori'];
                            $kategori->save();
                        }
                    }
                }
            }
            
            // Commit transaksi jika tidak ada masalah
            DB::commit();

            return response()->json(['message' => 'Perubahan berhasil diterapkan']);
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi error
            DB::rollback();
            return response()->json(['error' => 'Terjadi kesalahan saat memproses data'], 500);
        }
    }

    private function deleteCategory($id_kategori)
    {
        // Ambil semua id_berita yang terkait dengan id_kategori dari tabel IdDetail
        $id_berita_list = Detail::where('id_kategori', $id_kategori)->pluck('id_berita')->toArray();
        // Hapus semua paragraft yang memiliki id_berita dari id_berita_list
        Paragraft::whereIn('id_berita', $id_berita_list)->delete();
    
        // Hapus semua komentar berita yang memiliki id_berita dari id_berita_list
        KomentarBerita::whereIn('id_berita', $id_berita_list)->delete();
    
        // Hapus semua id_detail yang memiliki id_kategori
        Detail::where('id_kategori', $id_kategori)->delete();
        
        // Hapus semua berita yang terkait dengan id_berita_list
        Berita::whereIn('id_berita', $id_berita_list)->delete();
    
        
    
        // Hapus kategori dari tabel KategoriBerita
        KategoriBerita::where('id_kategori', $id_kategori)->delete();
    }
    
    private function updateCategory($id_kategori, $nama_kategori)
    {
        // Update kategori berdasarkan id_kategori
        KategoriBerita::where('id_kategori', $id_kategori)
            ->update(['nama_kategori' => $nama_kategori]);
    }

    public function getAllKategori()
    {
        $categories = DB::table('kategori_beritas')->select('nama_kategori')->get();

        return response()->json($categories);
    }
}


            
            