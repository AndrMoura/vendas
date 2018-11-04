@extends('layouts.app')

@section('content')
<link href = "{{ asset('css/main.css') }}" rel ="stylesheet">
<link href = "{{ asset('css/profile.css') }}" rel ="stylesheet">


    <div class="displayprofile">

    @if (session()->has('msg'))
        <div class="success">
            <p><strong>{{session()->get('msg')}}</strong> </p>
        </div>
    @endif

        <h3 id="userprofileh3">User Profile</h3>
        <div id="parent">
        <strong> <label> Name:</label> </strong>
        <p>{{$user->name}}</p>
        </div>

        <div id="parent">
        <strong> <label> Username:</label> </strong>
        <p>{{$user->username}}</p>
        </div>

        <div id="parent">
        <strong> <label> Email:</label> </strong>
        <p>{{$user->email}}</p>
        </div>

        <div id="parent">
        <strong> <label>Address:</label></strong>
        <p>{{$user->address}}</p>
        </div>

        <div id="parent">
        <strong> <label>City:</label></strong>
        <p>{{$user->city}}</p>
        </div>

        <div id="parent">
        <strong> <label>Codigo-Postal:</label></strong>
        <p>{{$user->codigopostal}}</p>
        </div>

        <div id="parent">
        <strong> <label>Phone-number:</label></strong>
        <p>{{$user->phone}}</p>
        </div>

         <div id="parent">
        <strong> <label>Created-at:</label></strong>
        <p>{{$user->created_at}}</p>
        </div>
    </div>
                        
@endsection