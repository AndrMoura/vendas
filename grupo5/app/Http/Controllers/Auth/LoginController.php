<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use Cookie;
use App\Product;
use App\Cart;
use App\User;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function authenticated($user)
    {
        $cart = Cookie::get('cart');
        $cart = json_decode( $cart, true);
        $this->setUserSession($user, $cart);

        $this->Cart_to_Database($cart);

        Cookie::queue(
            Cookie::forget('cart')
        );
    }

    protected function setUserSession($user, $cart)
    {
        $quantity = Auth::user()->cart()->sum('quantity');

        if($cart != null){

            foreach ($cart as $product){

                $product_cart = Cart::where('user_id', Auth::user()->id)->where('product_id',$product['product_id'])->first();
                $product_table = Product::where('id',$product['product_id'])->first();

                if($product_cart == null) {

                    if ($product_table->quantity < $product['quantity'])
                        continue;
                }else {
                    if($product_table->quantity < $product_cart->quantity + $product['quantity'])
                        continue;
                }

                $quantity += $product['quantity']; //do cookie
            }

        }

        if($quantity == null) {
            session(['quantity' => 0]);
        }

       session(['quantity' => $quantity]);
    }

    public function Cart_to_Database($cart){

        if($cart == null){
            return;
        }
        foreach ($cart as $product){ //cart do cookie

          $product_cart = Cart::where('user_id',  Auth::user()->id)
                ->where('product_id',$product['product_id'])->first();
          $product_table = Product::where('id',$product['product_id'])->first();

            if($product_cart == null) {

                if ($product_table->quantity < $product['quantity'])
                    continue;
            }else {
                if($product_table->quantity < $product_cart->quantity + $product['quantity'])
                    continue;
            }

          if($product_cart == null){

              $cart = new Cart;
              $cart->user_id = Auth::user()->id;
              $cart->product_id =  $product['product_id'];
              $cart->filepath = $product['filepath'];
              $cart->name =  $product['name'];
              $cart->quantity = $product['quantity'];
              $cart->price =  $product['price'];

              Auth::user()->cart()->save($cart);
          }
          else{

            $product_cart->quantity += $product['quantity'];
            Auth::user()->cart()->save($product_cart);
          }

        }
    }
}
