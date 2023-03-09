<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\ImageProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ImageController extends Controller
{
    public function upload(Request $request)
    {
        $file = $request->file('file');

        $validator = Validator::make($request->all(),[
            'file' => 'required|mimes:png,jpg,PNG,JPG',
            'product_id' => 'required',
            'name' => 'required',
            'enable'=> 'required'
        ]);
        
        if ($validator->fails()) {
            return response()->json(
                ['error' => $validator->errors(), 'status_code' => 400], 400);
            }
        if ($file) {
            $filename = $file->getClientOriginalName();
            $path = $file->storeAs('uploads', $filename, 'public');
            $url = url('storage/' . $filename);

            $insert = DB::table('images')->insertGetId([
                'name'=> $request->name,
                'file'=> $url,
                'enable'=>$request->enable
            ]);

            $insert_image_product = DB::table('product_images')->insert([
                'product_id'=> $request->product_id,
                'image_id'=> $insert
            ]);

            return response()->json(['message' => 'File uploaded successfully', 'path' => $path]);
        } else {
            return response()->json(['message' => 'No file was uploaded']);
        }
    }
    
}
