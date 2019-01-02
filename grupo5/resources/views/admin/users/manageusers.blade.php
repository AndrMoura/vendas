@extends('layouts.app')
<head>
    <title> User list</title>
</head>
@section('content')
    <link href = "{{ asset('css/main.css') }}" rel ="stylesheet">
    <link href = "{{ asset('css/userlist.css') }}" rel ="stylesheet">


    <div class="container">
        <label> Total number of users : <b> {{$allusers}}</b></label>
        <ol>
            @foreach($users as $user)

                <li> <a id="listuserid" href="/manage/users/{{$user->id}}">{{ $user-> name }} - {{$user->email}}</a></li>

            @endforeach
        </ol>
    </div>

@endsection

