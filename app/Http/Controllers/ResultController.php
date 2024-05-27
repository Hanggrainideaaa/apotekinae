<?php

namespace App\Http\Controllers;

use App\Models\Request as ModelsRequest;
use App\Models\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;


class ResultController extends Controller
{
    public function create(Request $request)
    {
        // Validasi input
        $validation = Validator::make($request->all(), [
            'request_id' => 'required|exists:requests,id',
            'price' => 'required|numeric',
        ]);

        if ($validation->fails()) {
            return response()->json($validation->errors(), 422);
        }

        // Mendapatkan objek request berdasarkan request_id
        $modelsRequest = ModelsRequest::findOrFail($request->request_id);

        // Mendapatkan user dari token
        $user = auth()->guard('api')->user();

        // Mendapatkan apotek_id dari klaim JWT
        $payload = JWTAuth::parseToken()->getPayload();
        $apotekId = $payload->get('apotek_id');

        // Membuat record baru di tabel Result
        $result = Result::create([
            'request_id' => $modelsRequest->id,
            'user_id' => $modelsRequest->user_id,
            'apotek_id' => $apotekId,
            'price' => $request->price,
            'isAccepted' => $request->isAccepted ?? false, // Default ke false jika tidak ada
            'isTaken' => $request->isTaken ?? false // Default ke false jika tidak ada
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Successfully created result',
            'data' => $result
        ], 201);
    }

    public function isVerified(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            'isAccepted' => 'required'
        ]);

        if ($validation->fails()) {
            return response()->json($validation->errors(), 422);
        }

        $apotek = Result::find($id);

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

    public function isTaken(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            'isTaken' => 'required'
        ]);

        if ($validation->fails()) {
            return response()->json($validation->errors(), 422);
        }

        $result = Result::find($id);

        if (!$result) {
            return response()->json([
                'success' => false,
                'message' => 'Data not found'
            ], 404);
        }

        try {
            $result->update($request->all());
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil di update',
                'data' => $result
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
