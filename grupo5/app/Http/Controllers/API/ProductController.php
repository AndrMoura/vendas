<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Product;
use App\User;
use Auth;
use Validator;

class ProductController extends Controller
{
    public function product(Request $request){

        return Product::where("id", $request->id)->first();
    }

    public function productPrice(Request $request){

        return Product::where("id", $request->id)->first()->price;
    }

    public function productName(Request $request){

        return Product::where("id", $request->id)->first()->name;
    }

    public function products(){

        return Product::all();
    }

    public function createProduct(Request $request){

        $user = Auth::user();
        if($user->role == 'user')  return response()->json(['error'=> 'You have no permission'], 401);

        $validator = Validator::make($request->all(), [
            'name' => 'min:1|max:100',
            'quantity' => 'digits_between:1,5',
            'price' => 'numeric|min:1|max:50000',
            'image' => 'required|image'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $file = $request->file('image');
        $filename = time()."-".$file->getClientOriginalName();

        $input = $request->all();
        $input['filepath'] = $filename;
        $product = Product::create($input);

        $file = $file->move('products/photos',$filename);
        return response()->json(['success'=>'Product added'], 200);
    }

}
