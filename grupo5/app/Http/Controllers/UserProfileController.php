<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\User;

class UserProfileController extends Controller
{
    public function index(){

        $user = Auth::user();
        return view('userprofile.userprofile', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('userprofile.userprofileedit', compact('user'));;
    }

    public function postEdit(Request $req, $id){
        
        $this->validate($req, array(
        'username' => 'required|min:5|max:255',
        'email' => "required|email",
        'address' => 'required|min:5|max:255',
        'city' => 'required|min:5|max:255',
        'codigopostal' => 'required|digits:7',
        'phone' => 'required|digits:9'
        ));

        $user = User::find($id);
        $user-> update($req->all());
        return redirect('/profile/'.$user->id);
    }

}
