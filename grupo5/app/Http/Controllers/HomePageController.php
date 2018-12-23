<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Cart;
use App\Product;

class HomePageController extends Controller
{
    
    public function index()
    {

        $products = Product::paginate(10);
        $productsall = Product::all();
        $count = $productsall->count();

        //$quantity = Cart::all()->where('user_id',Auth::user()->id)->sum('quantity');
        return view('homepage', compact('products', 'count'));
    }

    public function search(Request $req)
    {
        $product = Product::where('name', $req->name)->get();
        if($product != NULL)
            $count = $product->count();
        else{

            $count = 0;
        }
        if($req->ajax()){
            return response()->json($product);
            
        }
    return view('homepagesearch', compact('product', 'count'));

    }

    
    
}
