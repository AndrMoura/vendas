<?php

namespace App;
use App\User;
use App\Supplier;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function orders(){
        return $this->belongsToMany('App\Order','orders_products')->withPivot('quantity', 'unit_price');
     }

     public function suppliers(){
        return $this->belongsTo('App\Supplier','suppliers');
     }
}
