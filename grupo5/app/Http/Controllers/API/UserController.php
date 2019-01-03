<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Product;
use Illuminate\Support\Facades\Auth;
use Validator;

class UserController extends Controller
{

    public $successStatus = 200;

    public function login(){
        if(Auth::attempt(['username' => request('username'), 'password' => request('password')])){
            $user = Auth::user();
            $success['token'] =  $user->createToken('MyApp')-> accessToken;
            return response()->json(['success' => $success], $this-> successStatus);
        }
        else{
            return response()->json(['error'=>'Unauthorised'], 401);
        }
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'username' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
            'role' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        return response()->json(['success'=>'User created'], $this-> successStatus);
    }

    public function details()
    {
        $user = Auth::user();

        return response()->json(['success' => $user], $this-> successStatus);
    }

    public function update(Request $request){

        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'name' => 'nullable',
            'username' => 'unique:users|min:5|max:255',
            'email' => "nullable|email",
            'address' => 'nullable|min:5|max:255',
            'city' => 'nullable|min:5|max:255',
            'codigopostal' => 'nullable|regex:/^\d{4}-\d{3}?$/',
            'phone' => 'nullable|digits:9'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $user = User::find($user->id);
        if($user == null) return response()->json(['error' => 'user not found'], 404);

        $user->update($request->all());
        return response()->json(['success'=>'User updated'], $this-> successStatus);
    }
}
