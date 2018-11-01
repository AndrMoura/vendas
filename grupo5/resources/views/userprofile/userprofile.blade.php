@extends('layouts.app')

@section('content')
 <link href = "{{ asset('css/profile.css') }}" rel ="stylesheet">


    <body>

    <div class="userinfo">

      <h3> {{$user->username}} profile </h3>
        <ul>
        <li>{{$user->name}} </li>
        <li>{{$user->email}}</li>
        </ul>



    </div>







    </body>

@endsection