<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Cart;
use App\User;
use Auth;

class ProductController extends Controller
{
    public function index($id)
    {
        $product = Product::find($id);
        return view('products.product',compact('product'));
    }

    public function saveCart(Request $request){

        if(Auth::user()){

            $user = Auth::user();
            $insertProduct = Product::find($request->id);
            $products = Cart::all();

            $cart = Cart::where('product_id',$request->id)->first();

            if($cart  != null){

                $cart->quantity++;
                $cart->price = $cart->price + $cart->price;
                $cart->save();
                return view('cart.cart', compact('products'));
            }
            else{
                $cart = new Cart;
                $cart->user_id = $user->id;
                $cart->product_id =  $insertProduct->id;
                $cart->name =  $insertProduct->name;
                $cart->quantity = 1;
                $cart->price =  $insertProduct->price;

                $user->cart()->save($cart);
                $products = Cart::all();
            }

        }

        return view('cart.cart', compact('products'));
    }

    public function showCart(){
        return view('cart.cart');
    }
    
}
