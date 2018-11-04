<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Product;

class Supplier extends Model
{
    public function products(){
        return $this->hasMany('App\Product','products');
     }
}
