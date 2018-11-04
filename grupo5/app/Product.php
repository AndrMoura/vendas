<?php

namespace App;
use App\User;
use App\Supplier;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function users(){
        return $this->belongsToMany('App\User','products_users');
     }

     public function suppliers(){
        return $this->belongsTo('App\Supplier','suppliers');
     }
}
