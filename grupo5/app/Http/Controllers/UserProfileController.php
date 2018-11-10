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

    /*public function edit()
    {
        $user = Auth::user();
        return view('userprofile.userprofileedit', compact('user'));;
    }*/

    public function postEdit(Request $req, $id){

        $user = User::find($id);

        if($user->username == $req->username){

            $this->validate($req, array(
                'username' => 'required|nullable|min:5|max:255',
            ));
        }
        else {
            $this->validate($req, array(
                'username' => 'unique:users|nullable|min:5|max:255',
            ));
        }

        $this->validate($req, array(
            'email' => "required|nullable|email",
            'address' => 'nullable|min:5|max:255',
            'city' => 'nullable|min:5|max:255',
            'codigopostal' => 'nullable|regex:/^\d{4}-\d{3}?$/',
            'phone' => 'nullable|digits:9'
        ));

        $user-> update($req->all());
        session()->flash('msg', "Data saved with success!");
        return response('/profile/'.$user->id);
    }

}
