@extends('layouts.app')

@section('content')

<link href = "{{ asset('css/main.css') }}" rel ="stylesheet">
<link href = "{{ asset('css/profile.css') }}" rel ="stylesheet">
<link href = "{{ asset('css/tab.css') }}" rel ="stylesheet">
<link href = "{{ asset('css/collapse.css') }}" rel ="stylesheet">
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

<div class="tab">
    <button id='tab1' class="tablinks" >Profile</button>
    <button id='tab2' class="tablinks" >Orders</button>
</div>

<div class="profiletab">
<form action="/profile/{{Auth::user()->id}}" method="POST" >
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
</div>

<script>

    $(document).ready(function() {
        $('.ordertab').css('display', 'none');
    });

    $(document).on('click', "#tab1", function(e){

           $('.profiletab').css('display', 'block');
           $('.ordertab').css('display', 'none');
        }
    );
    $(document).on('click', "#tab2", function(e){

           $('.profiletab').css('display', 'none');
           $('.ordertab').css('display', 'block');
        }
    );

</script>

<script>

    $(document).on('click', ".collapse", function() {

        var $this = $(this).toggleClass('active');
        var content = $this.next().toggleClass('show');
        $('.collapse.active').not(this).removeClass('active').next().removeClass('show');

    });


</script>

<div class="ordertab">
    @for($i = 0; $i < $number_orders; $i++)

                <button class="collapse">
                    #{{$i+1}} Order - Date of Order: {{$orders[$i]['created_at']}}
                </button>
                <div class="content">
                    <table>
                        <th> Product Name </th>
                        <th> Quantity </th>
                        <th> Price </th>
                        <th> Total </th>
                        @foreach ($orders[$i]->products as $product)

                <tr>
                    <td>{{$product->pivot->product_id}}</td>
                    <td>{{$product->pivot->quantity}}</td>
                    <td>{{$product->pivot->unit_price}}</td>
                    <td>{{$product->pivot->quantity*$product->pivot->unit_price}}</td>
                </tr>

        @endforeach
        </table>
    </div>
    @endfor
</div>

<script>
    $(document).ready(function(){
        $(".success").click(function(){
            $(".success").hide(1000);
        });
    });
</script>
<hr>
@endsection