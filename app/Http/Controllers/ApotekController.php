<?php

namespace App\Http\Controllers;

use App\Models\apotek;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class ApotekController extends Controller
{
    public function index()
    {
        $data = apotek::orderBy('id')->with('users')->get();
        return response()->json([
            'status'=>true,
            'massage'=>'Succesfully get Data',
            'data'=>$data
        ],200);     
    }

    public function create(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'pharmacy_license_number' => 'required',
            'pharmacy_license_file' => 'required|mimetypes:application/pdf|max:40000',
            'pharmacits_practice_license' => 'required',
            'pharmacy_address' => 'required',
            'latitut' => 'required',
            'longitut' => 'required'
        ]);

            
        if ($validation->fails()) {
            return response()->json($validation->errors(), 422);
        }

        $user = auth()->guard('api')->user();

        $pharmacyLicenseFile = $request->file('pharmacy_license_file')->storeAs('public/pharmacy_files', Str::random(10) . '.' . $request->file('pharmacy_license_file')->extension());

        $pharmacyLicenseFileUrl = Storage::url($pharmacyLicenseFile);

        $apotek = apotek::create([
            'name'=> $request['name'],
            'pharmacy_license_number'=> $request['pharmacy_license_number'],
            'pharmacy_license_file'=> $pharmacyLicenseFileUrl,
            'pharmacy_address'=> $request['pharmacy_address'],
            'pharmacits_practice_license'=> $request['pharmacits_practice_license'],
            'latitut'=> $request['latitut'],
            'longitut'=> $request['longitut'],
            'isVerified'=> false,
            'user_id' => $user->id,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Successfully created apotek',
            'data' => $apotek
        ], 201);
    }

    public function show(string $id)
    {
        $data = apotek::find($id);
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

    public function update(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'pharmacy_license_number' => 'required',
            'pharmacy_license_file' => 'required',
            'pharmacits_practice_license' => 'required',
            'pharmacy_address' => 'required',
            'latitut' => 'required',
            'longitut' => 'required',
            'isVerified' => 'required'
        ]);

        if ($validation->fails()) {
            return response()->json($validation->errors(), 422);
        }

        $apotek = Apotek::find($id);

        if (!$apotek) {
            return response()->json([
                'success' => false,
                'message' => 'Data not found'
            ], 404);
        }

        try {
            $apotek->update($request->all());
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil di update',
                'data' => $apotek
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function destroy(string $id)
    {
        $apotek = Apotek::findOrFail($id);

        if (!$apotek) {
            return response()->json([
                'success' => false,
                'message' => 'Data not found'
            ], 404);
        }

        // Ambil nama file dari pharmacy_license_file
        $fileName = basename($apotek->pharmacy_license_file);

        try {
            // Hapus file dari penyimpanan publik
            Storage::delete('public/pharmacy_files/' . $fileName);

            // Hapus entri apotek dari database
            $apotek->delete();

            return response([
                'success' => true,
                'message' => 'Berhasil menghapus apotek'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function isVerified(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            'isVerified' => 'required'
        ]);

        if ($validation->fails()) {
            return response()->json($validation->errors(), 422);
        }

        $apotek = Apotek::find($id);

        if (!$apotek) {
            return response()->json([
                'success' => false,
                'message' => 'Data not found'
            ], 404);
        }

        try {
            $apotek->update($request->all());
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil di update',
                'data' => $apotek
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

}
