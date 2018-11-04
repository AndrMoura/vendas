@extends('layouts.app')

@section('content')

<link href = "{{ asset('css/main.css') }}" rel ="stylesheet">
<link href = "{{ asset('css/profile.css') }}" rel ="stylesheet">
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>


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
    </div>


    <a  href="/profile/{{Auth::user()->id}}/edit" class ="editsubmitprofile">Edit </a> 

<br>
<br>
<br>

<script>
    $(document).ready(function(){
        $(".success").click(function(){
            $(".success").hide(1000);
        });
    });
</script>
<hr>
@endsection