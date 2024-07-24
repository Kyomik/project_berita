<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Login;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public function login(Request $request)
{
    $credentials = $request->only('username', 'password');
    
    $validator = Validator::make($credentials, [
        'username' => 'required|string',
        'password' => 'required|string',
    ]);

    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }

    $login = Login::where('username', $credentials['username'])->first();

    if (!$login || !Hash::check($credentials['password'], $login->password)) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }
    
    $user = User::find($login->id_user);

    try {
        $token = JWTAuth::fromUser($user);
        
        $request->session()->put('user', [
            'id_user' => $user->id_user,
            'nama' => $user->nama_user,
            'hak_akses' => $user->hak_akses,
            'secret' => $token,
        ]);
        
        // Optionally, save the refresh token in the database

        if($user->hak_akses == "admin" || $user->hak_akses == "user_admin")
            return redirect()->route('admin/dashboard', ['token' => $token])->with('status', 'anda berhasil login');
        else
            return redirect()->route('/')->with('status', 'anda berhasil login');

    } catch (JWTException $e) {
        return response()->json(['error' => 'Could not create token'], 500);
    }
}



public function refreshToken(Request $request)
{
    $refreshToken = $request->refresh_token; // Assuming you send the refresh token in the request

    if (!$refreshToken) {
        return response()->json(['error' => 'Refresh token not provided'], 400);
    }

    try {
        $token = JWTAuth::setToken($refreshToken)->refresh(); // Refresh token
        $user = JWTAuth::setToken($token)->toUser();
        
        // Optionally, update the token in the database
        $login = Login::where('id_user', $user->id_user)->first();
        if ($login) {
            $login->update(['refresh_token' => $token]);
        }

        return response()->json([
            'token' => $token,
            'user' => [
                'id_user' => $user->id_user,
                'nama' => $user->nama_user,
            ]
        ]);
    } catch (JWTException $e) {
        return response()->json(['error' => 'Could not refresh token'], 500);
    }
}



    public function register(Request $request)
    {   
        // Validasi input
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:logins',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Buat pengguna baru
        $user = User::create([
            'nama_user' => $request->nama,
            'hak_akses' => 'pembaca', // Atur hak akses sesuai kebutuhan
        ]);

        // Simpan informasi login
        $login = Login::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'id_user' => $user->id_user,
        ]);

        return response()->json([
            'id_user' => $user->id_user,
            'nama' => $user->nama_user,
            'username' => $request->username,
        ], 201); // Gunakan status code 201 untuk menunjukkan resource telah berhasil dibuat
    }

    public function logout()
    {
        $cek = JWTAuth::invalidate(JWTAuth::getToken());
        if($cek){
            session()->flush();
            return response()->json(['message' => 'Successfully logged out']);
        }

        return response()->json(['error' => 'ada yang salah disini']);
    }

    public function me()
    {
        $user = JWTAuth::parseToken()->authenticate();
        return response()->json([
            'id_user' => $user->id_user,
            'nama' => $user->nama_user,
        ]);
    }
}
