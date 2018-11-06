<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;
use App\Product;
use App\Supplier;

class AdminController extends Controller
{
    public function products()
    {
        return view('admin.manageproducts');
    }



    public function users()
    {
        $users = User::paginate(10)->where('role','user');
        return view('admin.users.manageusers',compact('users'));   
    }

    public function manageUser($id)
    {
        $user = User::find($id);
        return view('admin.users.user',compact('user'));
    }

    public function viewProducts(){

       $products = Product::all();
       $suppliers = Supplier::all();
       $count = $products->count();
        return view('admin.products.listProducts', compact('products', 'count','suppliers'));
    }

    public function addProduct(Request $req) {

        $this->validate($req, array(
            'name' => '|min:1|max:255',
            'price' => 'digits_between:2,5'

        ));


        $product = new Product;
        $product->name = $req->name;
        $product->price = $req->price;
        $product->type = $req->type;
        $product->quantity = $req->quantity;
        $product->supplier_id = $req->supplier_id;
        $product->save();
        return response($product, 200)->header('Content-Type', 'text/plain');
             
    }

    public function postaddProduct(){

    }
}
