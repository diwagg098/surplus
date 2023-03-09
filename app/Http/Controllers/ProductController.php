<?php

namespace App\Http\Controllers;

use App\Models\CategoryProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Throwable;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = DB::table('products')
                ->leftJoin('category_products', 'category_products.product_id', '=','products.id')
                ->leftJoin('categories','categories.id','=','category_products.category_id')
                ->leftJoin('product_images','product_images.product_id', '=','products.id')
                ->leftJoin('images', 'images.id', '=','product_images.image_id')
                ->select('products.*', 'categories.name AS category_name','images.file AS image_url', 'images.name AS image_name')
                ->get();
        return response()->json([
            'message' => 'success',
            'status_code' => 200,
            'data' => $data
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            $validator = Validator::make($request->all(),[
                'name' => 'required',
                'description' => 'required',
                'enable' => 'required|boolean',
                'category_id' => 'required'
            ]);
            
            if ($validator->fails()) {
                return response()->json(
                    ['error' => $validator->errors(), 'status_code' => 400], 400);
                }
                
                $insert = Product::create([
                    'name' => $request->name,
                    'description' => $request->description,
                    'enable' => $request->enable
                ]);

                $insert_category_product = CategoryProduct::create([
                    'category_id' => $request->category_id,
                    'product_id' => $insert->id
                ]);
                
                return response()->json([
                    'status_code' => 200,
                    'message' => 'Success',
                    'data' => $insert
                ]);
        } catch (Throwable $e){
            return response()->json([
                'status_code' => 500,
                'message' => 'Internal Server Error'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = DB::table('products')
                ->where('products.id', $id)
                ->leftJoin('category_products', 'category_products.product_id', '=','products.id')
                ->leftJoin('categories','categories.id','=','category_products.category_id')
                ->leftJoin('product_images','product_images.product_id', '=','products.id')
                ->leftJoin('images', 'images.id', '=','product_images.image_id')
                ->select('products.*', 'categories.name AS category_name', 'categories.id AS category_id','images.file AS image_url', 'images.name AS image_name')
                ->first();

        if(!$data){
            return response()->json([
                'message' => 'no sql rows',
                'status_code' => 404,
                'data' => $data
            ]); 
        }
        return response()->json([
            'message' => 'success',
            'status_code' => 200,
            'data' => $data
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try{
            $detail = Product::find($id);
            if(!$detail){
            return response()->json([
                'message' => 'no sql rows',
                'status_code' => 404,
                'data' => $detail
            ]); 
        }
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'enable' => 'required|boolean',
            'category_id' => 'required'
        ]);
        
        if ($validator->fails()) {
            return response()->json(
                ['error' => $validator->errors(), 'status_code' => 400], 400);
            }
            
            $data = [
                'name' => $request->name,
                'enable' => $request->enable
            ];
            
            $update = Product::where('id', $id)->update($data);
            return response()->json([
                'message' => 'success',
                'status_code' => 200,
                'data' => Product::find($id)
            ]);
        } catch (Throwable $e){
            return response()->json([
                'status_code' => 500,
                'message' => 'Internal Server Error'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $detail = Product::find($id);
        if(!$detail){
            return response()->json([
                'message' => 'no sql rows',
                'status_code' => 404,
                'data' => $detail
            ]); 
        }

        $delete = Product::where('id', $id)->delete();
        $delete_category_product = CategoryProduct::where('product_id', $id)->delete();
        return response([
            'message' => 'success',
            'status_code' => 200,
            'data' => null
        ]);
    }
}
