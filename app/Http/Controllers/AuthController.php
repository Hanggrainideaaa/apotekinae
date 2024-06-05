<?php

namespace App\Http\Controllers;

use Error;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required'
        ]);

        if ($validation->fails()) {
            return response()->json($validation->errors(), 422);
        }
        
        $data = $request->only('email', 'password');

        if (!$token = auth()->guard('api')->attempt($data)) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau Password Anda salah'
            ], 401);
        }

        return response()->json([
            'success' => true,
            'users'    => auth()->guard('api')->user(),
            'token'   => $token
        ], 200);
    }
    public function loginMembers(Request $request)
    {
        try {

            $validation = Validator::make($request->all(), [
                'email' => 'required',
                'password' => 'required'
            ]);
            if ($validation->fails()) {
                return response()->json($validation->errors(), 422);
            }
            
            $credentials = $request->only('email', 'password');
    
            if (!$token = auth()->guard('api')->attempt($credentials)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email atau Password Anda salah'
                ], 401);
            }
    
            $user = auth()->guard('api')->user();
    
            // Periksa apakah pengguna memiliki apotek yang terdaftar
            if ($user->apotek) {
                $apotekId = $user->apotek->id;
                
                // Buat token dengan custom claims
                $token = JWTAuth::claims(['apotek_id' => $apotekId])->fromUser($user);
    
                return response()->json([
                    'success' => true,
                    'users'    => $user,
                    'apotek_id' => $apotekId,
                    'token'   => $token
                ], 200);
            }
    
            return response()->json([
                'success' => false,
                'message' => 'Anda belum mendaftarkan apotek'
            ], 400);

        } catch (Exception $e){
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()

            ], 500);
        }

        
    }
}
