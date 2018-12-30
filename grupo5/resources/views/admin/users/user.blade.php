@extends('layouts.app')

@section('content')
    <link href = "{{ asset('css/main.css') }}" rel ="stylesheet">
    <link href = "{{ asset('css/profile.css') }}" rel ="stylesheet">
    <link href = "{{ asset('css/table.css') }}" rel ="stylesheet">

    @if (session()->has('msg'))
        <div class="success">
            <p><strong>{{session()->get('msg')}}</strong> </p>
        </div>
    @endif

    <table id="table">

        <tr>
            <th colspan="2">User profile info</th>
        </tr>
        <tr>
            <td>Name</td>
            <td >{{$user->name}} </td>
        </tr>
        <tr>
            <td>Username</td>
            <td>{{$user->username}}</td>
        </tr>
        <tr>
            <td>Email</td>
            <td>{{$user->email}}</td>
        </tr>
        <tr>
            <td>Address</td>
            <td>{{$user->address}}</td>
        </tr>
        <tr>
            <td>City</td>
            <td>{{$user->city}}</td>
        </tr>
        <tr>
            <td>Codigo-Postal</td>
            <td>{{$user->codigopostal}}</td>
        </tr>
        <tr>
            <td>Phone-number</td>
            <td>{{$user->phone}}</td>
        </tr>
        <tr>
            <td>Created-at</td>
            <td>{{$user->created_at}}</td>
        </tr>

    </table>



@endsection