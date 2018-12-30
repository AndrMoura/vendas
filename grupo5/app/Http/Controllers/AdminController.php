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
        $users = User::where('role','user')->paginate(2);
        $allusers = User::all()->where('role','user')->count();
        return view('admin.users.manageusers',compact('users', 'allusers'));
    }

    public function manageUser($id)
    {
        $user = User::find($id);
        return view('admin.users.user',compact('user'));
    }

    public function viewProducts(){

       $products = Product::paginate(10);
       $suppliers = Supplier::all();
       $count = $products->count();
        return view('admin.products.listProducts', compact('products', 'count','suppliers'));
    }

    public function addProduct(Request $req) {


        $this->validate($req, [
            'name1' => 'min:1|max:100',
            'quantity1' => 'digits_between:1,5',
            'price1' => 'numeric|min:1|max:50000',
            'image' => 'required|image'

        ], [
            'name1.min' => 'The Name may not be less than 1 characters.',
            'quantity1.digits_between' => 'Quantity must be a digit between 1 and 5.',
            'price1.digits_between' => 'Price must be a digit between 1 and 5',
            'image.required' => 'An Image is required',
            'image.image' => 'The file must be an image'
        ]);

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

        $this->validate($req, [
            'dados.name' => 'min:1|max:15',
            'dados.price' => 'numeric|min:1|max:50000',
            'dados.quantity' => 'digits_between:1,5',
            'dados.supplier_id' => 'exists:suppliers,id'
        ], [
            'dados.name.min' => 'The Name may not be less than 1 characters.',
            'dados.name.max' => 'The Name may not be greater than 15 characters.',
            'dados.supplier_id.exists' => 'Must insert a valid supplier',
            'dados.price.digits_between' => 'Price must be a digit between 1 and 5',
            'dados.quantity.digits_between' => 'Quantity must be a digit between 1 and 5'
        ]);

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
