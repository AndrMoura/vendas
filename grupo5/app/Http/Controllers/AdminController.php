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
       $count = $products->count();
        return view('admin.products.listProducts', compact('products', 'count'));
    }

    public function addProduct() {
        $suppliers = Supplier::All();
        return view('admin.products.addProduct', compact('suppliers'));
    }

    public function postaddProduct(){

    }
}
