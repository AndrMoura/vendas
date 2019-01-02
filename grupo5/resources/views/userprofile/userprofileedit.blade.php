@extends('layouts.app')

@section('content')
<head>
      <title> User details</title>
</head>

<link href = "{{ asset('css/main.css') }}" rel ="stylesheet">
<link href = "{{ asset('css/profile.css') }}" rel ="stylesheet">


      <form action="/profile/{{Auth::user()->id}}" method="POST">
      <div class="displayprofile">
            <h3>Edit User Profile</h3>
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            
            <label for="name">Username</label>
            <input type="text" id="username" name="username" value="{{Auth::user()->username}}">
            @if ($errors->has('username'))
            <strong>{{ $errors->first('username') }}</strong>
            @endif

            <label for="email"> Email</label>
            <input type="text" id="email" name="email" value="{{Auth::user()->email}}">
            @if ($errors->has('email'))
            <strong>{{ $errors->first('email') }}</strong>
            @endif

            <label for="address">Address</label>
            <input type="text" id="adr" name="address" value="{{Auth::user()->address}}">
            @if ($errors->has('address'))
            <strong>{{ $errors->first('address') }}</strong>
            @endif

            <label for="city">City</label>
            <input type="text" id="city" name="city" value="{{Auth::user()->city}}">
            @if ($errors->has('city'))
            <strong>{{ $errors->first('city') }}</strong>
            @endif

            <label for="codigopostal">Codigo-Postal</label>
            <input type="text" id="cpostal" name="codigopostal" value="{{Auth::user()->codigopostal}}">
            @if ($errors->has('codigopostal'))
            <strong>{{ $errors->first('codigopostal') }}</strong>
            @endif

            <label for="city">Phone-number</label>
            <input type="text" id="phonenumber" name="phone" value="{{Auth::user()->phone}}">
            @if ($errors->has('phone'))
            <strong>{{ $errors->first('phone') }}</strong>
            @endif

            @if ($errors->any())
                  <div >
                        <ul>
                              @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                              @endforeach
                        </ul>
                  </div>
            @endif

      </div> 
            <input type="submit" class="editsubmitprofile" value="Save"> 
      </form>
      
             
@endsection
