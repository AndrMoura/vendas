<?php

namespace App;
use App\User;
use App\Supplier;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 'quantity', 'price', 'filepath','supplier_id', 'type'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

    public function orders(){
        return $this->belongsToMany('App\Order','orders_products')->withPivot('quantity', 'unit_price', 'product_name');
     }

     public function suppliers(){
        return $this->belongsTo('App\Supplier','suppliers');
     }
}
