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
            'name1' => 'min:1|max:100',
            'quantity1' => 'digits_between:1,5',
            'price1' => 'digits_between:1,5'

        ));

        $file = $req->file('image');
        $filename = time()."-".$file->getClientOriginalName();


        $product = new Product;
        $product->name = $req->name1;
        $product->price = $req->price1;
        $product->type = $req->type1;
        $product->quantity = $req->quantity1;
        $product->supplier_id = $req->supplier_id1;
        $product->filepath = $filename;
        $product->save();

        $file = $file->move('products/photos',$filename);
        return response()->json($product);

    }

    public function editProduct(Request $req){


        $this->validate($req, array(
            'dados.name' => 'min:1|max:100',
            'dados.price' => 'digits_between:1,5',
            'dados.quantity' => 'digits_between:1,5',
            'dados.supplier_id' => 'exists:suppliers,id'
        ));

        $product = Product::where('id', $req->id)->first();
        $colname = $req->colname;
        $product->$colname = $req->dados[$colname];
        $product->save();

    }

    public function deleteProduct(Request $req){

        $product = Product::where('id', $req->id)->first();

        $product->delete();
    }
}
