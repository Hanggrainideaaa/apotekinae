<?php

namespace App\Http\Controllers;

use App\Models\Request as ModelsRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class RequestController extends Controller
{
    public function index()
    {
        $requests = ModelsRequest::all();

        return response()->json([
            'status' => true,
            'data' => $requests
        ], 200);
    }
    
public function create(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:4048'
        ]);

            
        if ($validation->fails()) {
            return response()->json($validation->errors(), 422);
        }

        $users = auth()->user(); 

        $imagePath = $request->file('image')->store('public/img');
        $imageUrl = Storage::url($imagePath);

        $req = ModelsRequest::create([
            'user_id' => $users->id,
            'image' => $imageUrl
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Successfully request receipe',
            'data' => $req
        ], 201);
    }
}
