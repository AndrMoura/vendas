<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Cart;
use App\User;
use Auth;
use Cookie;

class ProductController extends Controller
{
    public function index($id)
    {
        $product = Product::find($id);
        return view('products.product',compact('product'));
    }

    public function saveCart(Request $request){

        $insertProduct = Product::find($request->id);

        if(Auth::user()){

            $updateCartSession = session('quantity');

            $updateCartSession = $updateCartSession + 1;
            session(['quantity' => $updateCartSession]);

            $user = Auth::user();
            $cart = Cart::where('user_id', $user->id)
            ->where('product_id',$request->id)->first();

            if($cart  != null){

                $cart->quantity++;
                $user->cart()->save($cart);
                $total = 0;
                $products = Cart::all()->where('user_id', $user->id);
                foreach($products as $product) {
                    $total += $product->price * $product->quantity;
                }

                return view('cart.cart', compact('products', 'total'));
            }
            else{
                $cart = new Cart;
                $cart->user_id = $user->id;
                $cart->product_id =  $insertProduct->id;
                $cart->filepath = $insertProduct->filepath;
                $cart->name =  $insertProduct->name;
                $cart->quantity = 1;
                $cart->price =  $insertProduct->price;

                $user->cart()->save($cart);
                $products = Cart::all()->where('user_id', $user->id);
                $total = 0;
                foreach($products as $product) {
                    $total += $product->price * $product->quantity;
                }
            }
            return view('cart.cart', compact('products', 'total'));

        }




        else {
            $hasFound = false;
            if ($request->hasCookie('cart')) {
                $products = Cookie::get('cart');

                $products = json_decode( $products, true);

                foreach ($products as &$product) {

                    if($product['product_id']  == $request->id){
                        $product['quantity'] += 1;
                        $hasFound = true;
                        break;
                    }
                }
                if(!$hasFound){

                    $newProduct = array($insertProduct->id => array(
                        'product_id' => $insertProduct->id,
                        'filepath' => $insertProduct->filepath,
                        'name' => $insertProduct->name,
                        'quantity' => 1,
                        'price' => $insertProduct->price
                    ));

                    $products = array_merge($products,$newProduct);
                    Cookie::queue('cart', json_encode( $products), 60);
                    $total = $this->getTotalCookie($products);
                    $products =json_decode(json_encode($products));
                    return view('cart.cart', compact('products', 'total'));

                }
                else {

                    Cookie::queue('cart', json_encode($products), 60);
                    $total = $this->getTotalCookie($products);
                    $products =json_decode(json_encode($products));

                    return view('cart.cart', compact('products', 'total'));
                }

            }
            else {

                $products = array(

                    $insertProduct->id => array(
                        'product_id' => $insertProduct->id,
                        'filepath' => $insertProduct->filepath,
                        'name' => $insertProduct->name,
                        'quantity' => 1,
                        'price' => $insertProduct->price
                    ));

                Cookie::queue('cart', json_encode( $products), 60);
                $total =  $products[$insertProduct->id]['quantity'];
                $products =json_decode(json_encode($products));
                return view('cart.cart', compact('products', 'total'));

            }
        }

    }

    public function saveCartHomePage(Request $request)
    {

        $insertProduct = Product::find($request->id);

        if (Auth::user()) {

            $user = Auth::user();
            $cart = Cart::where('user_id', $user->id)
                ->where('product_id', $request->id)->first();


            if ($cart != null) {

                $cart->quantity++;
                $user->cart()->save($cart);

                $cart = Cart::all()->where('user_id', $user->id)->sum('quantity');

                session(['quantity' => $cart]);
                return response()->json($cart);
            } else {
                $cart = new Cart;
                $cart->user_id = $user->id;
                $cart->product_id = $insertProduct->id;
                $cart->filepath = $insertProduct->filepath;
                $cart->name = $insertProduct->name;
                $cart->quantity = 1;
                $cart->price = $insertProduct->price;
                $user->cart()->save($cart);

                $cart = Cart::all()->where('user_id', $user->id)->sum('quantity');
                session(['quantity' => $cart]);

                return response()->json($cart);
            }

        }




        else {
            $hasFound = false;
            if ($request->hasCookie('cart')) {
                $cart = Cookie::get('cart');

                $cart = json_decode($cart, true);

                foreach ($cart as &$product) {

                   if($product['product_id']  == $request->id){
                           $product['quantity'] += 1;
                           $hasFound = true;
                           break;
                   }
                }
                if(!$hasFound){

                    $newProduct = array($insertProduct->id => array(
                            'product_id' => $insertProduct->id,
                            'filepath' => $insertProduct->filepath,
                            'name' => $insertProduct->name,
                            'quantity' => 1,
                            'price' => $insertProduct->price
                       ));

                      $cart = array_replace($cart,$newProduct);

                     $quantity = $this->getQuantityCookie($cart);
                     Cookie::queue('cart', json_encode($cart), 60);

                     return response()->json($quantity);
                }
                else {

                    Cookie::queue('cart', json_encode($cart), 60);
                    $quantity = $this->getQuantityCookie($cart);
                    return response()->json($quantity);
                }

            }
            else {

            $cart = array(

                $insertProduct->id => array(
                    'product_id' => $insertProduct->id,
                    'filepath' => $insertProduct->filepath,
                    'name' => $insertProduct->name,
                    'quantity' => 1,
                    'price' => $insertProduct->price
                ));

                Cookie::queue('cart', json_encode($cart), 60);

                return response()->json($cart[$insertProduct->id]['quantity']);

            }

        }

    }

    public function getTotalCookie(array $cart){

        $total = 0;

        foreach ($cart as $product){

            $total += $product['quantity'] * $product['price'];
        }

        return $total;
    }

    public function getQuantityCookie(array $cart){

        $quantity = 0;

        foreach ($cart as &$product) {

            $quantity += $product['quantity'];
        }

        return $quantity;

    }

    public function updateCart(Request $request){

    if(Auth::user()){

        $user = Auth::user();
        $cart = Cart::where('user_id',$user->id)->where('id',$request->id)->first();

        $cart->quantity = $request->quantity;

        $user->cart()->save($cart);

        $cart = Cart::where('user_id',$user->id)->find($request->id);

        $cart_all = Cart::all()->where('user_id',$user->id)->sum('quantity');

        session(['quantity' =>  $cart_all]);
        return response()->json($cart);
    }

    else{

        if ($request->hasCookie('cart')) {

            $products = Cookie::get('cart');

            $products =json_decode($products, true);

            $productUpdate = 0;

            foreach($products as &$product){

                if($product['product_id'] == $request->id){

                    $product['quantity'] = intval($request->quantity); //request vem em string

                    $productUpdate = $product;
                }
            }

            Cookie::queue('cart', json_encode($products), 60);

            return response()->json($productUpdate);

        }
    }
}



    public function showCart(Request $request)
    {
        if(Auth::user()){
            $products = Cart::all()->where('user_id', Auth::user()->id);
            $total = 0;

            foreach($products as $product) {
                $total += $product->price * $product->quantity;
            }
            if($total != 0){

                return view('cart.cart',compact('products','total'));
            }
            else {
                return view('cart.cart');
            }

        }
        else{

            if ($request->hasCookie('cart')) {
                $products = Cookie::get('cart');

                $products = json_decode( $products, true);


                $total = $this->getTotalCookie($products);
                $products =json_decode(json_encode($products));

                return view('cart.cart', compact('products', 'total'));
                }

            else {

                return view('cart.cart');

            }
        }

    }

    public function deleteCart(Request $request){


        if(Auth::user()){

          $user =  Auth::user();

          $cart = Cart::where('user_id', $user->id)->where('id',$request->id)->first();

          $cart->delete();
          $quantity = Cart::all()->where('user_id',$user->id)->sum('quantity');

          session(['quantity' => $quantity]);
          return response()->json($cart);
        }
        else{

            if ($request->hasCookie('cart')) {
                $products = Cookie::get('cart');

                $products = json_decode( $products, true);
                unset($products[$request->id]);



                Cookie::queue('cart', json_encode($products), 60);


            }
        }
    }

    
}
