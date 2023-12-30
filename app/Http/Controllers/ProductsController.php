<?php

namespace App\Http\Controllers;

use App\Models\Products ;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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

        $usersResponse = Http::get('http://localhost:3000/users');
        $users = $usersResponse->json();
    

        $mergedData = $products->map(function ($product) use ($users) {
            $userproduct = collect($users['data'])->firstWhere('id', $product['userID']);
            $product['username'] = $userproduct['username'] ?? null;
            unset($product['userID']); 
            return $product;
        });
    

        return response()->json([
            'status' => 'success',
            'data' => $mergedData 
        ], 200);
        
    }
    
    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $product = new Products();
        $product->id = $request->id;
        $product->userID = $request->userID;
        $product->name = $request->name;
        $product->description = $request->description;
        $product->thumbnailURL = $request->thumbnailURL;
        
        $product->save();

        unset($product['created_at']);
        unset($product['updated_at']);

        $usersResponse = Http::get('http://localhost:3000/users');
        $users = $usersResponse->json();

        $user = collect($users['data'])->firstWhere('id', $product['userID']);
        $product['username'] = $user['username'] ?? null;
        unset($product['userID']); 

        
        return response()->json([
            'status'=>'success',
            'data'=>$product
        ], 200);
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
            ], 200);
        }
    
        $usersResponse = Http::get('http://localhost:3000/users');
        $users = $usersResponse->json();

        $user = collect($users['data'])->firstWhere('id', $product['userID']);
        $product['username'] = $user['username'] ?? null;
        unset($product['userID']); 

      
        
        return response()->json([
            'status' => 'success',
            'data' => $product
        ], 200);

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
