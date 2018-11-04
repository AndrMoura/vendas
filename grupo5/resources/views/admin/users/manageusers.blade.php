@extends('layouts.app')

@section('content')
<link href = "{{ asset('css/main.css') }}" rel ="stylesheet">
<link href = "{{ asset('css/userlist.css') }}" rel ="stylesheet">


<ol>
@foreach($users as $user)

    <li> <a id="listuserid" href="/manage/users/{{$user->id}}">{{ $user-> name }}</a></li>
    
@endforeach
<ol>

 </ul>                           
@endsection