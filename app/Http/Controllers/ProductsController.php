<?php

namespace App\Http\Controllers;

use App\Models\Products ;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()

    {
       
        $products = Products::select('id','name','description','thumbnailURL','userID')->get();

        if($products->count()>0){
            return response()->json([
                'status' => 'success',
                'data' => $products 
            ], 200);
            
        }else{
            return response()->json([
                'status' => 404,
                'data' => 'No records found' 
            ], 404);
            
        }
        
    }
    
    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator =Validator::make($request->all(),[
            
            'name'=>'required',
            'description'=>'required',
            'thumbnailURL'=>'required',
            'userID'=>'required',
        ]);
        

        if($validator->fails()){
            return response()->json([
                'status'=>'422',
                'errors'=>$validator->messages()
            ], 422);
        }else{

            $product =Products::create([
            
                'name' => $request->name,
                'description' => $request->description,
                'thumbnailURL' => $request->thumbnailURL,
                'userID' => $request->userID,
            ]);
        }
        
        unset($product['created_at']);
        unset($product['updated_at']);

        if($product){
            return response()->json([
                'status'=>'success',
                'data'=>$product
            ], 200);
        }else{
            return response()->json([
                'status'=>500,
                'message'=>"something went wrong"
            ], 200);
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
        $product = Products::select('id','name','description','thumbnailURL','userID')->find($id);

        if (!$product) {
            return response()->json([
                'status' => 'success',
                'message' => 'Product not found',
                'data' => null
            ], 404);
        }else{
        return response()->json([
            'status' => 'success',
            'data' => $product
        ], 200);
        }

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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Products::find($id);

        if (!$product){
            return response()->json([
                'status'=>'error',
                'massage'=>'Data not found'
            ], 404);

        }

        $product->delete();

        return response()->json([
            'status'=>'success',
            'massage'=>'any'
        ],200);

    }
}
