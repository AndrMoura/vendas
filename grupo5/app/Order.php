<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public function products(){
        return $this->belongsToMany('App\Product','orders_products')->withPivot('quantity', 'unit_price', 'product_name');
    }
}
