<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class Api extends Controller
{
    public function products(){

        return Product::all();
    }

    public function product(Request $request){

        return Product::where("id", $request->id)->first();
    }

    public function productPrice(Request $request){

        return Product::where("id", $request->id)->first()->price;
    }

    public function productName(Request $request){

        return Product::where("id", $request->id)->first()->name;
    }
}
