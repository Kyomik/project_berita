<?php

namespace App\Http\Controllers;

use App\Models\View;
use App\Models\Draft;
use App\Models\Berita;
use App\Models\Detail;
use App\Models\Paragraft;
use Illuminate\Http\Request;
use App\Models\KategoriBerita;
use App\Models\KomentarBerita;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BeritaController extends Controller
{
    // ini selesai
    public function index(){
        $kategoris = BeritaController::getAllKategoris();

        $beritabyKategori = [];

        $beritaPopuler = BeritaController::getBeritaPopuler();
        $beritaLatest = BeritaController::getBeritaLatest();
        
        $categories = KategoriBerita::take(3)->get();

        foreach ($categories as $category) {
            // Subquery untuk mendapatkan paragraf pertama dari setiap berita
            $subquery = DB::table('paragrafts')
                ->select('id_berita', DB::raw('MIN(id_paragraft) as min_paragraft'))
                ->whereIn('status_paragraft', ['publish'])
                ->groupBy('id_berita');
        
            $beritas = DB::table('beritas')
                ->join('details', 'beritas.id_berita', '=', 'details.id_berita')
                ->leftJoinSub($subquery, 'first_paragraph', function($join) {
                    $join->on('beritas.id_berita', '=', 'first_paragraph.id_berita');
                })
                ->leftJoin('paragrafts as p', function($join) {
                    $join->on('first_paragraph.id_berita', '=', 'p.id_berita')
                        ->on('first_paragraph.min_paragraft', '=', 'p.id_paragraft');
                })
                ->where('details.id_kategori', $category->id_kategori)
                ->where('beritas.status_berita', 'publish')
                ->select(
                    'beritas.id_berita',
                    'beritas.judul_berita',
                    'details.views',
                    'beritas.tanggal_berita',
                    'beritas.gambar',
                    DB::raw('CASE 
                        WHEN LENGTH(p.isi_paragraft) > 150 
                        THEN CONCAT(SUBSTRING(p.isi_paragraft, 1, 150), "...")
                        ELSE p.isi_paragraft 
                    END as isi_paragraf')
                )
                ->orderBy('details.views', 'desc')
                ->take(3)
                ->get();
        
            $beritabyKategori[] = [
                'nama_kategori' => $category->nama_kategori,
                'beritas' => $beritas // Reset keys to numeric
            ];
        }
        
        
        // dd($beritabyKategori);
        $beritabyKategori = json_encode($beritabyKategori);
        $beritabyKategori = json_decode($beritabyKategori);

        return view('home', compact('kategoris', 'beritaLatest', 'beritabyKategori', 'beritaPopuler'));
    }

    public function getBeritaPopuler(){
    $beritaPopuler = DB::table('beritas as b')
        ->select('b.id_berita', 'b.gambar', 'b.judul_berita', 'd.views', DB::raw('MAX(p.isi_paragraft) as isi_paragraft'), 'b.tanggal_berita')
        ->join('details as d', 'b.id_berita', '=', 'd.id_berita')
        ->leftJoin('paragrafts as p', function($join) {
            $join->on('b.id_berita', '=', 'p.id_berita')
                ->where('p.status_paragraft', 'publish');
        })
        ->where('b.status_berita', 'publish')
        ->groupBy('b.id_berita', 'b.gambar', 'b.judul_berita', 'd.views', 'b.tanggal_berita')
        ->orderBy('d.views', 'desc')
        ->limit(6)
        ->get();

    return $beritaPopuler;
}

public function getBeritaLatest()
{
    $beritaLatest = DB::table('beritas as b')
    ->join('details as d', 'b.id_berita', '=', 'd.id_berita')
    ->leftJoin(DB::raw('(
        SELECT DISTINCT id_berita, 
            MAX(isi_paragraft) as isi_paragraft
        FROM paragrafts
        WHERE status_paragraft = "publish"
        GROUP BY id_berita
    ) as p'), 'b.id_berita', '=', 'p.id_berita')
    ->where('b.status_berita', 'publish')
    ->select(
        'b.id_berita',
        'b.judul_berita',
        'b.tanggal_berita',
        'b.gambar',
        'd.views',
        DB::raw('CASE 
            WHEN LENGTH(p.isi_paragraft) > 300
            THEN CONCAT(SUBSTRING(p.isi_paragraft, 1, 300), "...")
            ELSE p.isi_paragraft 
        END as isi_paragraft')
    )
    ->orderBy('b.tanggal_berita', 'desc')
    ->limit(10)
    ->get();

return $beritaLatest;

}


    public function uploadTampilan(){
        $kategoris = KategoriBerita::all();

        return view('admin.normal-admin.berita.upload', compact('kategoris'));
    }

    // selesai
    public function upload(Request $request)
    {
        $user = $request->attributes->get('user'); // Mendapatkan user dari request


        // Validasi input
        $validator = Validator::make($request->all(), [
            'judul_berita' => 'string|max:255',
            'gambar_berita' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'isi_paragraft' => 'array',
            'isi_paragraft.*' => 'string|max:500',
            'id_kategori' => 'integer|exists:kategori_beritas,id_kategori',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $gambarPath = null;
        
        if ($request->hasFile('gambar_berita')) {
            $image = $request->file('gambar_berita');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/images', $name);

            $gambarPath = 'storage/images/' . $name;
        }

        // Buat berita baru
        $berita = Berita::create([
            'judul_berita' => $request->judul_berita,
            'gambar' => $gambarPath,
            'status_berita' => 'draft',
            'tanggal_berita' => now(),
            'updated_at' => now()
        ]);
       
       
        // return response()->json($nextId);
        // Tambah paragraf
        foreach ($request->isi_paragraft as $isi) {
            $lastParagraft = Paragraft::latest()->first();
            $nextId = $lastParagraft ? $lastParagraft->id_paragraft += 1 : 1;
            DB::table('paragrafts')->insert([
                'id_paragraft' => $nextId,
                'id_berita' => $berita->id_berita,
                'isi_paragraft' => $isi,
                'status_paragraft' => 'new',
                'updated_at' => now(),
                'created_at' => now()
            ]);
        }
        // Tambah id detail
        DB::table('details')->insert([
            'keterangan' => 'upload',
            'views' => 0,
            'id_user' => $user->id_user,
            'id_berita' => $berita->id_berita,
            'id_kategori' => $request->id_kategori,
        ]);
        // return response()->json($user);

        return response()->json([
            'redirect' => './../draft',
            'status' => 'Berhasil menginput berita'
        ]);
    }

//    selesai
public function draftTampilan(Request $request) 
{
    $user = $request->attributes->get('user'); // Mendapatkan user dari request
    $currentPage = LengthAwarePaginator::resolveCurrentPage();

    $perPage = 10;
    $offset = ($currentPage - 1) * $perPage;

    $query = Berita::join('details', 'beritas.id_berita', '=', 'details.id_berita')
                   ->join('kategori_beritas', 'details.id_kategori', '=', 'kategori_beritas.id_kategori')
                   ->select('beritas.id_berita', 'beritas.judul_berita', 'kategori_beritas.nama_kategori', 
                            DB::raw('DATEDIFF(NOW(), beritas.updated_at) as jumlah_hari'),
                            'details.keterangan');

    if ($user['hak_akses'] === 'admin') {
        $query->addSelect('details.views')
              ->where(function ($query) use ($user) {
                  $query->where('beritas.status_berita', 'publish')
                        ->whereIn('details.keterangan', ['upload', 'edit'])
                        ->orWhere(function ($query) use ($user) {
                            $query->where('beritas.status_berita', 'draft')
                                  ->where('details.id_user', $user->id_user);
                        });
              });
    } elseif ($user['hak_akses'] === 'user_admin') {
        $query->leftJoin('users as admin_users', 'details.id_user', '=', 'admin_users.id_user')
              ->addSelect('admin_users.nama_user as nama_admin')
              ->where(function ($query) {
                  $query->where('beritas.status_berita', 'publish')
                        ->whereIn('details.keterangan', ['upload', 'edit'])
                        ->orWhere('beritas.status_berita', 'draft');
              });
    }

    // Dapatkan total item
    $total = $query->count();

    // Dapatkan data dengan pagination
    $berita = $query->offset($offset)->limit($perPage)->get();
    
    // Buat LengthAwarePaginator
    $berita = new LengthAwarePaginator($berita, $total, $perPage, $currentPage, [
        'path' => $request->url(),
        'pageName' => 'page',
    ]);
    
    // Tentukan view berdasarkan hak akses
    $view = ($user['hak_akses'] === 'admin') ? 'admin.normal-admin.berita.draft' : 'admin.super-admin.berita.draft';

    return view($view, compact('berita'));
}

    public function beritaByKategoriTampilan(Request $request, $nama_kategori){
        $user = $request->attributes->get('user'); // Mendapatkan user dari request
        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        $perPage = 10;
        $offset = ($currentPage - 1) * $perPage;
        
        if ($user != null && $user->hak_akses == "user_admin") {
            $query = DB::table('beritas as b')
            ->join('details as d', 'b.id_berita', '=', 'd.id_berita')
            ->join('users as u', 'd.id_user', '=', 'u.id_user')
            ->join('kategori_beritas as k', 'd.id_kategori', '=', 'k.id_kategori')
            ->leftJoin('paragrafts as p', 'b.id_berita', '=', 'p.id_berita')
            ->where('k.nama_kategori', $nama_kategori)
            ->where('b.status_berita', 'publish')
            ->where('d.keterangan', 'publish')
            ->select(
                'b.id_berita',
                'b.judul_berita',
                'b.tanggal_berita',
                'u.nama_user as nama_admin',
                DB::raw('MAX(p.updated_at) as tanggal_update'),
                'd.views'
            )
            ->groupBy(
                'b.id_berita',
                'b.judul_berita',
                'b.tanggal_berita',
                'u.nama_user',
                'd.views'
            )
            ->orderBy('b.tanggal_berita', 'desc');

            $total = $query->count();
            
            // Dapatkan data dengan pagination
            $berita = $query->offset($offset)->limit($perPage)->get();

            // Buat LengthAwarePaginator
            $berita = new LengthAwarePaginator($berita, $total, $perPage, $currentPage, [
                'path' => $request->url(),
                'pageName' => 'page',
            ]);

            return view('admin.super-admin.kategori.index', compact('berita'));
        }

        $query = DB::table('beritas as b')
        ->join('details as d', 'b.id_berita', '=', 'd.id_berita')
        ->join('kategori_beritas as k', 'd.id_kategori', '=', 'k.id_kategori')
        ->leftJoin(DB::raw('(SELECT id_berita, isi_paragraft, updated_at FROM paragrafts WHERE status_paragraft = "publish" AND (id_paragraft, updated_at) IN (SELECT id_paragraft, MAX(updated_at) FROM paragrafts GROUP BY id_berita, id_paragraft)) as p'), 'b.id_berita', '=', 'p.id_berita')
        ->where('k.nama_kategori', $nama_kategori)
        ->where('b.status_berita', 'publish')
        ->select(
            'b.judul_berita',
            'b.gambar',
            'b.tanggal_berita',
            'd.views',
            DB::raw(
                "CASE 
                    WHEN LENGTH(p.isi_paragraft) > 150 
                    THEN CONCAT(SUBSTRING(p.isi_paragraft, 1, 150), '...')
                    ELSE p.isi_paragraft 
                END as isi_paragraft"
            )
        )
        ->groupBy('b.id_berita', 'b.gambar', 'b.judul_berita', 'd.views', 'b.tanggal_berita')
        ->orderBy('d.views', 'desc');
            
                $total = $query->count();

                // Dapatkan data dengan pagination
                $beritas = $query->offset($offset)->limit($perPage)->get();
                
                // Buat LengthAwarePaginator
                $beritas = new LengthAwarePaginator($beritas, $total, $perPage, $currentPage, [
                    'path' => $request->url(),
                    'pageName' => 'page',
                ]);
            // Convert to JSON if needed
            // $beritas = json_encode($beritas);
            // $beritas = json_decode($beritas);
            
            $kategoris = BeritaController::getAllKategoris();
            $beritaPopuler = BeritaController::getBeritaPopuler();
                    
            return view('kategori', compact('kategoris', 'beritaPopuler', 'beritas'));   
    }

    public function beritaBySearchTampilan(Request $request){
        $text = $request->judul;

        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        $perPage = 10;
        $offset = ($currentPage - 1) * $perPage;

        $query = DB::table('beritas as b')
            ->join('details as d', 'b.id_berita', '=', 'd.id_berita')
            ->leftJoin(DB::raw('(SELECT id_berita, isi_paragraft, updated_at FROM paragrafts WHERE status_paragraft = "publish" AND (id_paragraft, updated_at) IN (SELECT id_paragraft, MAX(updated_at) FROM paragrafts GROUP BY id_berita, id_paragraft)) as p'), 'b.id_berita', '=', 'p.id_berita')
            ->where('b.judul_berita', 'like', '%' . $text . '%')
            ->where('b.status_berita', 'publish')
            ->select(
                'b.judul_berita',
                'b.gambar',
                'b.tanggal_berita',
                'd.views',
                DB::raw(
                    "CASE 
                        WHEN LENGTH(p.isi_paragraft) > 150 
                        THEN CONCAT(SUBSTRING(p.isi_paragraft, 1, 150), '...')
                        ELSE p.isi_paragraft 
                    END as isi_paragraft"
                )
            )
            ->groupBy('b.id_berita', 'b.gambar', 'b.judul_berita', 'd.views', 'b.tanggal_berita')
            ->orderBy('d.views', 'desc');

        $total = $query->count();
        $beritas = $query->offset($offset)->limit($perPage)->get();

        // Buat LengthAwarePaginator
        $beritas = new LengthAwarePaginator($beritas, $total, $perPage, $currentPage, [
            'path' => $request->url(),
            'pageName' => 'page',
        ]);

        // Ambil kategori dan berita populer
        $kategoris = BeritaController::getAllKategoris();
        $beritaPopuler = BeritaController::getBeritaPopuler();

        return view('kategori', compact('kategoris', 'beritaPopuler', 'beritas')); 
        
    }

    public function publishTampilan(Request $request)
    {
        $user = $request->attributes->get('user'); // Mendapatkan user dari request

        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        // Tentukan jumlah item per halaman
        $perPage = 10;

        // Hitung offset untuk query
        $offset = ($currentPage - 1) * $perPage;

        // Dapatkan user saat ini

        // Inisialisasi query
        $query = Berita::join('details', 'beritas.id_berita', '=', 'details.id_berita')
                    ->join('kategori_beritas', 'details.id_kategori', '=', 'kategori_beritas.id_kategori')
                    ->select('beritas.id_berita', 'beritas.judul_berita', 'kategori_beritas.nama_kategori', 
                                'beritas.tanggal_berita as tanggal_upload', 'details.views')
                    ->where('beritas.status_berita', 'publish')
                    ->whereIn('details.keterangan', ['publish'])
                    ->where('details.id_user', $user->id_user);

        // Dapatkan total item
        $total = $query->count();

        // Dapatkan data dengan pagination
        $berita = $query->offset($offset)->limit($perPage)->get();

        // Buat LengthAwarePaginator
        $berita = new LengthAwarePaginator($berita, $total, $perPage, $currentPage, [
            'path' => $request->url(),
            'pageName' => 'page',
        ]);

        // Tentukan view berdasarkan hak akses
        $views = 'admin.normal-admin.berita.publish';

        return view($views, compact('berita'));
    }
    
    
    public function editTampilan(Request $request, $id)
    {
        $status = $request->query('status');

        $berita = Berita::with('detail')->findOrFail($id);

        // Ambil subquery paragraft terakhir yang diperbarui
        $subQuery = DB::table('paragrafts as p1')
            ->select('p1.*')
            ->where('id_berita', $id)
            // ->whereIn('p1.status_paragraft', $status == 'draft' ? ['edit', 'new'] : ['publish'])
            ->whereRaw('p1.updated_at = (SELECT MAX(p2.updated_at) FROM paragrafts as p2 WHERE p2.id_paragraft = p1.id_paragraft)')
            ->orderBy('id_paragraft', 'asc')
            ->get();

        // Assign paragraf ke berita
        $berita->paragraf = $subQuery;
        // dd($berita->paragraf);
        // Ambil semua kategori
        $kategoris = KategoriBerita::all();
        return view('admin.normal-admin.berita.edit', compact('berita', 'kategoris'));
    }


    public function preview(Request $request, $id)
    {
        $user = $request->attributes->get('user'); // Mendapatkan user dari request
        
        $status = $request->query('status');
        $berita = null;
        $paragrafts = collect();
        $view = 'preview';
        if ($user != null) {
            $layout = 'layouts.admin';
            try {
                    switch ($user->hak_akses) {        
                        case 'admin':
                            $berita = Berita::where('id_berita', $id)
                                ->whereHas('detail', function ($query) use ($user) {
                                    $query->where('id_user', $user->id_user);
                                })
                                ->firstOrFail();
            
                            $paragrafts = $this->getParagrafts($id, $status);
                            
                            break;
                                    
                        case 'user_admin':
                            $berita = Berita::where('id_berita', $id)->firstOrFail();
            
                            $paragrafts = $this->getParagrafts($id, $status);
                            break;
                        default:
                            return response()->json(['error' => 'Unauthorized'], 401);
                    }    
                    if($status == 'draft')
                        $view = 'admin.draft';
            } catch (ModelNotFoundException $e) {
                return response()->json(["message" => "Data not found"], 404);
            }
        }else{
            
            if(session('user') != null)
                $this->addViewBerita(session('user')['id_user'], $id);


            $berita = Berita::where('judul_berita', $id)
                ->where('status_berita', 'publish')
                ->firstOrFail();
            
            $paragrafts = $this->getParagrafts($berita->id_berita, 'publish');

            $layout = 'layouts.app';
            $kategoris = BeritaController::getAllKategoris();
            $beritaPopuler = BeritaController::getBeritaPopuler();
        }
        

        $berita = [
            'id_berita' => $berita->id_berita,
            'judul_berita' => $berita->judul_berita,
            'tanggal_berita' => $berita->tanggal_berita,
            'gambar' => $berita->gambar,
            'paragrafs' => $paragrafts->map(function ($paragraft) {
                return [
                    'status_paragraf' => $paragraft->status_paragraft,
                    'isi_paragraf' => $paragraft->isi_paragraft,
                ];
            }),
        ];
        

        $berita = json_encode($berita);
        $berita = json_decode($berita);

        if ($user != null)
            return view($view, compact('berita', 'layout'));
        else
            return view($view, compact('berita', 'layout', 'kategoris', 'beritaPopuler'));
    }

    public function getAllKategoris(){
        $kategoris = KategoriBerita::all();
        $kategoris = json_encode($kategoris);
        $kategoris = json_decode($kategoris);

        return $kategoris;
    }
 
    public function update(Request $request, $id_berita){
        $validator = Validator::make($request->all(), [
            'new_judul_berita' => 'string|max:255',
            'new_gambar_berita' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'id_kategori' => 'integer',
            'isi_paragraft' => 'array',
            'isi_paragraft.*' => 'string|max:500',
            'id_paragraft' => 'array',
            'id_paragraft.*' => 'integer',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $berita = Berita::with('detail')->findOrFail($id_berita);
        $id_detail = $berita->detail;

        $gambarPath = null;
        $fileUpdated = false;
        $gambarPath = null;
        
        if ($request->hasFile('new_gambar_berita')) {
            $image = $request->file('new_gambar_berita');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/images', $name);

            $gambarPath = 'storage/images/' . $name;
            $fileUpdated = true;
        }

        // Update tabel berita jika ada perubahan
        if ($berita->judul_berita != $request->new_judul_berita || $fileUpdated || $id_detail->id_kategori != $request->id_kategori) {
            
            if($id_detail->keterangan == "upload" || $id_detail->keterangan == "publish"){
                
                Draft::create([
                    'id_berita' => $berita->id_berita,
                    'judul_berita' => $berita->judul_berita,
                    'gambar' => $gambarPath,
                    'id_kategori' => $id_detail->id_kategori,
                    'updated_at' => now()
                ]);
            }
            if($fileUpdated){
                $berita->update(['gambar' => $gambarPath]);
            }
            
            $berita->update([
                'judul_berita' => $request->new_judul_berita,
                'status_berita' => 'draft'
            ]);
            

            $id_detail->update([
                'id_kategori' => $request->id_kategori
            ]);

        }

        // Update tabel detail
        $id_detail->update([
            'keterangan' => 'edit',
        ]);
        $berita->update([
            'updated_at' => now()
        ]);
        // return $request->all();    
        try{
            foreach ($request->isi_paragraft as $key => $paragraft) {
                if($request->id_paragraft[$key] == "8998721"){
                    $lastParagraft = Paragraft::orderBy('id_paragraft', 'desc')->first();
                    $nextId = $lastParagraft ? $lastParagraft->id_paragraft += 1 : 1;

                        Paragraft::create([
                            'id_paragraft' => $nextId,
                            'id_berita' => $berita->id_berita,
                            'isi_paragraft' => $paragraft,
                            'status_paragraft' => 'new',
                            'updated_at' => now(),
                            'created_at' => now()
                        ]);
                }else{
                    $id_paragraft = Paragraft::where('id_paragraft', $request->id_paragraft[$key]);
                    if(!empty($id_paragraft)){
                        // return response()->json($paragraft);
                        $existingCategory = Paragraft::where('isi_paragraft', $paragraft)
                            ->first();
                        // return response()->json($existingCategory);
                        if(empty($existingCategory)){
                            // return response()->all($id_paragraft);
                            Paragraft::create([
                                'id_paragraft' => $request->id_paragraft[$key],
                                'id_berita' => $berita->id_berita,
                                'isi_paragraft' => $paragraft,
                                'status_paragraft' => 'edit',
                            ]);
                        }
                    }else{

                    }
                }
                
            }
        }catch(\Exception $e){}

        return response()->json([
            'redirect' => './../draft',
            'status' => 'Berhasil menginput berita'
        ]);
    }

    // selesai hanya fungsi bantu
    private function getParagrafts($id, $status)
    {
        switch ($status) {
            case 'draft':
                $paragrafts = Paragraft::where('id_berita', $id)
                                 ->whereIn('status_paragraft', ['edit', 'new', 'publish'])
                                 ->orderBy('id_paragraft', 'asc')
                                 ->orderBy('updated_at', 'desc')
                                 ->get();
                $uniqParagraft = $paragrafts->unique('id_paragraft')->values();
                return $uniqParagraft;

            case 'publish':
            default:
                return Paragraft::where('id_berita', $id)
                    ->where('status_paragraft', 'publish')
                    ->orderBy('id_paragraft', 'asc')
                    ->get();
        }
    }
    // selesai
    public function accBerita(Request $request, $id_berita){
        try {
            $user = $request->attributes->get('user'); // Mendapatkan user dari request
            
            if ($user['hak_akses'] !== 'user_admin') {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            
            // Mulai transaksi database
            DB::beginTransaction();

            // Update tabel 'berita'
            $berita = Berita::findOrFail($id_berita);
            $berita->status_berita = 'publish';
            if ($berita->detail->keterangan == 'upload') {
                $berita->tanggal_berita = now();
            }
            $berita->save();

            // // Update tabel 'detail'
            $detail = Detail::where('id_berita', $id_berita)->firstOrFail();
            $detail->keterangan = 'publish';
            $detail->save();

            // / Temukan semua id_paragraft unik yang terkait dengan id_berita ini
            $paragraftIds = Paragraft::where('id_berita', $id_berita)
                ->pluck('id_paragraft')
                ->unique();

            foreach ($paragraftIds as $paragraftId) {
                // Ambil paragraf dengan updated_at terbaru untuk setiap id_paragraft
                $latestParagraft = Paragraft::where('id_berita', $id_berita)
                    ->where('id_paragraft', $paragraftId)
                    ->orderBy('updated_at', 'desc')
                    ->first();

                // Hapus paragraf lain dengan id_paragraft yang sama kecuali yang terbaru
                Paragraft::where('id_berita', $id_berita)
                    ->where('id_paragraft', $paragraftId)
                    ->where('updated_at', '!=', $latestParagraft->updated_at)
                    ->delete();
            }
            
            // Update tabel 'paragraft' - Publish paragraf baru dan edit
            Paragraft::where('id_berita', $id_berita)
                    ->whereIn('status_paragraft', ['new', 'edit'])
                    ->update(['status_paragraft' => 'publish']);

            // Commit transaksi
            Draft::where('id_berita', $id_berita)->delete();

            DB::commit();

            return response()->json(['message' => 'Berhasil acc berita', 'redirect' => './']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Failed to approve news: ' . $e->getMessage()], 500);
        }
    }


    public function batalBerita(Request $request, $id_berita)
    {
        try {
            $user = $request->attributes->get('user'); // Mendapatkan user dari request
            
            if ($user['hak_akses'] !== 'user_admin') {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            
            // Mulai transaksi database
            DB::beginTransaction();

            

            // / Temukan semua id_paragraft unik yang terkait dengan id_berita ini
            $paragraftIds = Paragraft::where('id_berita', $id_berita)
                ->pluck('id_paragraft')
                ->unique();

            foreach ($paragraftIds as $paragraftId) {
                // Ambil paragraf dengan updated_at terbaru untuk setiap id_paragraft
                $early = Paragraft::where('id_berita', $id_berita)
                    ->where('id_paragraft', $paragraftId)
                    ->orderBy('updated_at', 'asc')
                    ->first();

                // Hapus paragraf lain dengan id_paragraft yang sama kecuali yang terbaru
                Paragraft::where('id_berita', $id_berita)
                    ->where('id_paragraft', $paragraftId)
                    ->where('updated_at', '!=', $early->updated_at)
                    ->delete();
            }
            
            // Update tabel 'paragraft' - Publish paragraf baru dan edit
            Paragraft::where('id_berita', $id_berita)
                    ->whereIn('status_paragraft', ['new', 'edit'])
                    ->update(['status_paragraft' => 'publish']);
            
            //Get tabel 'draft'
            $draft = Draft::where('id_berita', $id_berita)->firstOrFail();

            // Update tabel 'berita'
            $berita = Berita::findOrFail($id_berita);
            $berita->status_berita = 'publish';
            $berita->judul_berita = $draft->judul_berita;
            $berita->gambar = $draft->gambar;
            $berita->save();

            // Update tabel 'detail'
            $detail = Detail::where('id_berita', $id_berita)->firstOrFail();
            $detail->id_kategori = $draft->id_kategori;
            $detail->keterangan = 'publish';
            $detail->save();

            // Delete tabel 'draft'
            $draft->delete();

            // Commit transaksi
            DB::commit();

            return response()->json(['message' => 'Berhasil Membatalkan Pembaharuan', 'redirect' => './']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Failed to approve news: ' . $e->getMessage()], 500);
        }
    }

    public function getAllBerita(Request $request)
    {
        try {
            $page = $request->query('page', 1);
            $offset = ($page - 1) * 5;

            $beritas = Berita::whereHas('detail', function ($query) {
                    $query->where('publish', 1);
                })
            
                ->with(['detail' => function ($query) {
                    $query->select('id_berita', 'views'); 
                }])
                ->orderBy('created_at', 'desc')
                ->offset($offset)
                ->limit(10)
                ->get();

            $beritas->each(function ($berita) {
                $berita->views = $berita->detail ? $berita->detail->views : null;
            });

            return response()->json($beritas);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch news: ' . $e->getMessage()], 500);
        }
    }




    











    


    // public function getBeritaByAdmin(Request $request)
    // {
    //     try {
    //         // Perform authorization check for admin access
    //         if (auth()->user()->hak_akses != 'admin' && auth()->user()->hak_akses != 'super_admin') {
    //             return response()->json(['error' => 'Unauthorized access'], 403);
    //         }

    //         // Fetch news articles based on admin's session
    //         $beritas = Berita::with('idDetail')
    //             ->whereHas('idDetail', function ($query) {
    //                 $query->where('id_user', auth()->user()->id); // Example: fetch based on admin's ID
    //             })
    //             ->get();

    //         return response()->json($beritas);
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => 'Failed to fetch news: ' . $e->getMessage()], 500);
    //     }
    // }





    public function getBeritaByKategori($id_kategori, Request $request)
    {
        try {
            // Validate category ID
            $kategori = KategoriBerita::findOrFail($id_kategori);

            // Fetch news articles based on category
            $beritas = Berita::with('idDetail')
                ->whereHas('idDetail', function ($query) use ($id_kategori) {
                    $query->where('id_kategori', $id_kategori);
                })
                ->get();

            return response()->json($beritas);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch news: ' . $e->getMessage()], 500);
        }
    }


    public function getTerpopulerBerita(Request $request)
    {
        try {
            // Fetch popular news articles based on views or likes
            $beritas = Berita::with('idDetail')
                ->orderBy('idDetail.views', 'desc') // Example: fetch based on views
                ->get();

            return response()->json($beritas);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch popular news: ' . $e->getMessage()], 500);
        }
    }

    public function deleteBerita(Request $request, $id_berita)
{
    try {
        $user = $request->attributes->get('user'); // Mendapatkan user dari request

        // Fetch news article based on ID

        if ($user->hak_akses != 'user_admin') {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        DB::beginTransaction();
        $berita = Berita::findOrFail($id_berita);

        // Delete related records from 'details' table
        Detail::where('id_berita', $id_berita)->delete();

        // Delete related records from 'paragrafts' table
        Paragraft::where('id_berita', $id_berita)->delete();

        // Optionally, delete related records from 'komentar_berita' table
        KomentarBerita::where('id_berita', $id_berita)->delete();

        // Delete news article from 'berita' table
        $berita->delete();

        DB::commit();

        return response()->json([
            'redirect' => './',
            'message' => 'Berhasil mendelete berita'
        ]);
    } catch (\Exception $e) {
        DB::rollback();
        return response()->json(['error' => 'Failed to delete news: ' . $e->getMessage()], 500);
    }
}


public function addViewBerita($id_user, $id_berita)
{
    DB::beginTransaction(); // Mulai transaksi

    try {
        $check = View::where('id', $id_user)
                    ->where('judul_berita', $id_berita)
                    ->first();
        if(!$check){
            // Increment views count in 'id_detail' table
            $berita = Berita::where('judul_berita', $id_berita)->firstOrFail();
            $idDetail = Detail::where('id_berita', $berita->id_berita)->firstOrFail();
            $idDetail->views += 1;
            $idDetail->save();

            View::create([
                'id' => $id_user,
                'judul_berita' => $id_berita
            ]);
        }

        DB::commit(); // Selesaikan transaksi

        return response()->json(['message' => 'View added successfully']);
    } catch (\Exception $e) {
        DB::rollback(); // Rollback transaksi jika terjadi kesalahan
        return response()->json(['error' => 'Failed to add view: ' . $e->getMessage()]);
    }
}




    // selesai
    public function addKomentarBerita($id_berita, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'isi_komentar' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {
            // Fetch news article based on ID
            $berita = Berita::findOrFail($id_berita);

            // Insert comment into 'komentar_beritas' table
            $komentar = new KomentarBerita();
            $komentar->id_berita = $berita->id_berita;
            $komentar->id_user = 2; // Misalnya, set user ID di sini
            $komentar->isi_komentar = $request->isi_komentar; // Ganti dengan 'isi_komentar'
            $komentar->tanggal_komentar = now(); // Tambahkan tanggal komentar
            $komentar->save();

            return response()->json(['message' => 'Comment added successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to add comment: ' . $e->getMessage()], 500);
        }
    }

    public function getKomentar($id_berita)
    {
        try {
            // Fetch all comments for a news article
            $komentars = KomentarBerita::where('id_berita', $id_berita)->get();
            
            return response()->json($komentars);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch comments: ' . $e->getMessage()], 500);
        }
    }
}
