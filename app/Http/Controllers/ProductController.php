<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Events\validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\validator;
use App\Models\Product;


class ProductController extends Controller
{
  public function store(Request $request) 
  {
    $validator = Validator::make($request->all(),
    [
        'product_name'=>'required|max:50',
        'product_type'=>'required|in:makanan,minuman',
        'product_price'=>'required|numeric',
        'expired_at'=>'required|date'
    ]);

    if($validator->fails())
    {
        return response()->json($validator->messages())->setStatusCode(422);
    }

$payload=$validator->validate();

    Product::create([
        'product_name'=>$payload['product_name'],
        'product_type'=>$payload['product_type'],
        'product_price'=>$payload['product_price'],
        'expired_at'=>$payload['expired_at']
    ]);

    return response()->json([
        'msg'=>'Data produk berhasil di simpan'
    ],201);
   }

   function showAll(){
    $Products=Product::all();
    return response()->json([
            'msg'=>'Data produk keseluruhan',
            'data'=> $Products
    ],200);

   }

   function showByid($id){
        $Product=Product::where('id',$id)->first();

        if ($Product) {
            // code...
            return response()->json([
                'msg'=>'Data produk dengan ID:' .$id,
                'data'=>$Product
            ],200);
        }

        return response()->json([
            'msg'=> 'Data produk dengan ID: ' .$id.' tidak ditemukan',
        ],404);

   }

   function showByName($product_name){
        $Product=Product::where('product_name','LIKE','%'.$product_name.'%')->get();

        if ($Product->count() > 0) {
            // code...
            return response()->json([
                'msg'=>'Data produk dengan nama yang mirip:' .$product_name,
                'data'=>$Product
            ],200);
        }

        return response()->json([
            'msg'=> 'Data produk dengan nama yang mirip: ' .$product_name.' tidak ditemukan',
        ],404);

   }

   public function update(Request $request,$id){
    $validator = Validator::make($request->all(),[
        'product_name'=>'required|max:50',
        'product_type'=>'required|in:makanan,minuman',
        'product_price'=>'required|numeric',
        'expired_at'=>'required|date'
    ]);

    if($validator->fails())
    {
        return response()->json($validator->messages())->setStatusCode(422);
    }

    $payload=$validator->validated();

        Product::where('id',$id)->update([
        'product_name'=>$payload['product_name'],
        'product_type'=>$payload['product_type'],
        'product_price'=>$payload['product_price'],
        'expired_at'=>$payload['expired_at']
    ]);

    return response()->json([
        'msg'=>'Data produk berhasil di ubah'
    ],201);

   }
   
   public function delete($id){
        $Product=Product::where('id',$id)->get();

        if ($Product) {
            Product::where('id',$id)->delete();
            // code...
            return response()->json([
                'msg'=>'Data produk dengan ID:' .$id. 'berhasil dihapus'
            ],200);
        }

        return response()->json([
            'msg'=> 'Data produk dengan ID: ' .$id. 'tidak ditemukan'
        ],404);

    }
}