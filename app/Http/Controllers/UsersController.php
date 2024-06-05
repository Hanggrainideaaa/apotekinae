<?php

namespace App\Http\Controllers;

use App\Mail\VerificationEmail;
use App\Models\users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    public function index()
    {
        $data = users::orderBy('id')->get();
        return response()->json([
            'status'=>true,
            'massage'=>'Succesfully get Data',
            'data'=>$data
        ],200);     
    }
    public static function verification($token)
    {
       $user = users::where('token_verifications', $token)->first();
    
       if($user) {
          $user->update([
             'token_verifications' => '',
             'email_verified_at' => now(),
          ]);
          return ('/welcome');
       }
       else {
          return abort(404);
       }
    }

    public function create(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique',
            'password' => 'required|min:8|string',
        ]);

        if ($validation->fails()) {
            return response()->json($validation->errors(), 422);
        }

        $verificationToken = Str::random(60);

        $user = users::create([
            'name'=> $request['name'],
            'email'=> $request['email'],
            'password' => Hash::make($request['password']),
            'role_id' => 2,
            'token_verifications' => $verificationToken,
        ]);

        Mail::to($user->email)->send(new VerificationEmail($user));
        return response()->json([
            'success' => 'true'
        ],409);

    }

    public function createMitra(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'password' => 'required|min:8|string',
        ]);

        if ($validation->fails()) {
            return response()->json($validation->errors(), 422);
        }

        $verificationToken = Str::random(60);

        $user = users::create([
            'name'=> $request['name'],
            'email'=> $request['email'],
            'password' => Hash::make($request['password']),
            'role_id' => 3,
            'token_verifications' => $verificationToken,
        ]);

        Mail::to($user->email)->send(new VerificationEmail($user));
        return response()->json([
            'success' => 'true'
        ],409);

    }

    public function show(string $id)
    {
        $data = users::find($id);
        if($data){
            return response()->json([
                'status'=>true,
                'message'=>'Data Founded',
                'data'=>$data
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>'Data Not Found',                
            ],400);
        }
    }

    public function update(Request $request, users $user)
    {
        try {
            $validation = Validator::make($request->all(),[
                'name' => 'required',
                'email' => 'required',
            ]);

            if ($validation->fails()) {
                return response()->json($validation->errors(), 422);
            }
        
            $user->update($request->all());
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil di update',
                'data' => $user
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function destroy(users $user)
    {
        $user->delete();
        return response([
            'success' => true,
            'message' => 'Berhasil menghapus user'
        ], 200);
    }
}
