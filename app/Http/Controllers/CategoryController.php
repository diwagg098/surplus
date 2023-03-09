<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\CategoryProduct;
use Illuminate\Support\Facades\Validator;
use Throwable;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Category::all();
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
                'enable' => 'required|boolean'
            ]);
            
            if ($validator->fails()) {
                return response()->json(
                    ['error' => $validator->errors(), 'status_code' => 400], 400);
                }
                
                $insert = Category::create([
                    'name' => $request->name,
                    'enable' => $request->enable
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
        $data = Category::find($id);

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
            $detail = Category::find($id);
            if(!$detail){
            return response()->json([
                'message' => 'no sql rows',
                'status_code' => 404,
                'data' => $detail
            ]); 
        }
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'enable' => 'required|boolean'
        ]);
        
        if ($validator->fails()) {
            return response()->json(
                ['error' => $validator->errors(), 'status_code' => 400], 400);
            }
            
            $data = [
                'name' => $request->name,
                'enable' => $request->enable
            ];
            
            $update = Category::where('id', $id)->update($data);
            return response()->json([
                'message' => 'success',
                'status_code' => 200,
                'data' => Category::find($id)
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
        $detail = Category::find($id);
        if(!$detail){
            return response()->json([
                'message' => 'no sql rows',
                'status_code' => 404,
                'data' => $detail
            ]); 
        }

        $delete = Category::where('id', $id)->delete();
        $delete_category_product = CategoryProduct::where('category_id', $id)->delete();
        return response([
            'message' => 'success',
            'status_code' => 200,
            'data' => null
        ]);
    }
}
