<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\User;
use App\Order;
use App\Product;


class UserProfileController extends Controller
{
    public function index(){

        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)->get(); //todas as orders
        $number_orders = Order::where('user_id', $user->id)->count();
        $products = Product::all();

        return view('userprofile.userprofile', compact('user', 'orders', 'products', 'number_orders'));
    }

    public function getOrdersId(){

        $orders = Order::where('user_id', Auth::user()->id)->get();
        $orders = $orders->toArray();
        return $orders;
    }
    public function postEdit(Request $req, $id){

        $user = User::find($id);

        if($user->username == $req->username){

            $this->validate($req, array(
                'username' => 'required|nullable|min:5|max:255',
            ));
        }
        else {
            $this->validate($req, array(
                'username' => 'unique:users|nullable|min:5|max:255',
            ));
        }

        $this->validate($req, array(
            'email' => "required|nullable|email",
            'address' => 'nullable|min:5|max:255',
            'city' => 'nullable|min:5|max:255',
            'codigopostal' => 'nullable|regex:/^\d{4}-\d{3}?$/',
            'phone' => 'nullable|digits:9'
        ));

        $user-> update($req->all());
        session()->flash('msg', "Data saved with success!");
        return response('/profile/'.$user->id);
    }

}
