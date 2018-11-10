@extends('layouts.app')

@section('content')

<link href = "{{ asset('css/main.css') }}" rel ="stylesheet">
<link href = "{{ asset('css/profile.css') }}" rel ="stylesheet">
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>

<style>
    td[contentEditable] {
        background:white;
        color:black;
    }

    #alert {
        padding: 20px;
        background-color: #f44336;
        color: white;
    }

    #success {
        padding: 20px;
        background-color: #6dbc31;
        color: white;
    }

</style>

<script>

    function editUser(){

        var id = $('#name').parents().attr('id');

        var name = $('#name').val();
        var username = $('#username').val();
        var email = $('#email').val();
        var address = $('#address').val();
        var city = $('#city').val();
        var codigopostal = $('#codigopostal').val();
        var phone = $('#phone').val();

        $.ajax({
            method: "POST",
            url: "http://127.0.0.1:8000/profile/{{Auth::user()->id}}",
            headers: {"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')},
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                id: id,
                name: name,
                username: username,
                email : email,
                address : address,
                city : city,
                codigopostal: codigopostal,
                phone: phone
            },
            datatype: 'json',
            contentType: 'application/x-www-form-urlencoded',
            error: function (data) {

                console.log("ERROR?");

                var json   = data.responseJSON;

                var errors = json['errors'];

                $("#alert").css('display', 'block')

                for(var error in errors){

                    $("#edit_error").text(errors[error][0]);
                }
            }
        }).done(function(){

            $("#success").css('display', 'block')
            $("#edit_success").text("Data saved with success!");
        })

    }

    $(document).ready(function() {
        $("#submit").click(function (event) {
            editUser();

        });
    });

    $(document).ready(function() {
        $("#alert").click(function () {
            $('#alert').hide("slow");
        });
    });

    $(document).ready(function() {
        $("#success").click(function () {
            $('#success').hide("slow");
        });
    });


</script>

<div id="alert" style="display: none">
    <label id="edit_error" ></label>
</div>

<div id="success" style="display: none">
    <label id="edit_success" ></label>
</div>

<form action="/profile/{{Auth::user()->id}}" method="POST">
    <div class="displayprofile" id="{{Auth::user()->id}}">
        <h3>Edit User Profile</h3>
        <input type="hidden" name="_token" value="{{csrf_token()}}">

        <label for="name">Name</label>
        <input type="text" id="name" value="{{Auth::user()->name}}">

        <label for="name">Username</label>
        <input type="text" id="username" value="{{Auth::user()->username}}">

        <label for="email"> Email</label>
        <input type="text" id="email"  value="{{Auth::user()->email}}">

        <label for="address">Address</label>
        <input type="text" id="address" value="{{Auth::user()->address}}">

        <label for="city">City</label>
        <input type="text" id="city"  value="{{Auth::user()->city}}">

        <label for="codigopostal">Codigo-Postal</label>
        <input type="text" id="codigopostal"  value="{{Auth::user()->codigopostal}}">


        <label for="city">Phone-number</label>
        <input type="text" id="phone" value="{{Auth::user()->phone}}">


    </div>
    <input  id="submit" class="editsubmitprofile" value="Save">
</form>

<script>
    $(document).ready(function(){
        $(".success").click(function(){
            $(".success").hide(1000);
        });
    });
</script>
<hr>
@endsection