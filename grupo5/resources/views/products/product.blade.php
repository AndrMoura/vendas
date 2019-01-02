@extends('layouts.search')

@section('content')

<head>
    <title> {{$product->name}}</title>
</head>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://www.paypalobjects.com/api/checkout.js"></script>
<link href = "{{ asset('css/main.css') }}" rel ="stylesheet">
<link href = "{{ asset('css/productpage.css') }}" rel ="stylesheet">
<link href = "{{ asset('css/windowAlert.css') }}" rel ="stylesheet">

<script>

    paypal.Button.render({
        env: 'sandbox',
        payment: function(data, actions) {

            return actions.request.post('/create-payment', {
                _token: '{{csrf_token() }}',
                checkType: false,
                id: "{{$product->id}}"
            })
                .then(function(res) {

                    if(res.hasOwnProperty('Error')){
                        alert(res.error);
                    }
                    return res.id;
                })
        },
        onAuthorize: function(data, actions) {

            return actions.request.post('/execute-payment', {
                _token: '{{csrf_token() }}',
                paymentID: data.paymentID,
                payerID: data.payerID,
                checkType: false
            })
                .then(function (res) {
                    console.log(res);
                    $('.maxcontainer').remove();
                    $('#paypal-button').remove();
                    $("body").append(noItems);
                    $(".cartlabel").text(0);
                })
        }
    }, '#paypal-button');


    $(document).ready(function() {
        $("#cart").click(function () {

        $('#form').submit();
        });
    });

 $(document).ready(function() {

        $("#textsearch").keypress(function (event) {

            if(event.keyCode == 13)
            {
                console.log($(this).val());
                searchVal = $(this).val();
                window.open("/home/search?name="+searchVal,"_self");
            }
        });

    });

    $(document).ready(function() {
        $(".modal").click(function () {
            $('.modal').hide("slow");
        });
    });

    $(document).ready(function() { //FAZER FUNÇAO FICHEIRO SEPARADO

        var checkLogin = "{{{ (Auth::user()) ? Auth::user() : null }}}";
        console.log(checkLogin);
        if(checkLogin){

            $('.cartlabel').text({{Session::get('quantity')}});
        }
        else {

            var coded = "{{Cookie::get('cart') != "" ? Cookie::get('cart') : ""}}";

            if(coded != ""){

                var cart = jQuery.parseJSON($.parseHTML(coded)[0].textContent);
                var sum = 0;

                for (var product in cart) {

                    sum +=cart[product]['quantity'];
            }
                $('.cartlabel').text(sum);
            }
            else{
                $('.cartlabel').text(0);
            }

        }
    });

</script>


<div id="fb-root"></div>

<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = 'https://connect.facebook.net/pt_PT/sdk.js#xfbml=1&version=v3.2&appId=1148634605302466';
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

@if(session('success'))
    <div id="myModal" class="modal">

        <div class="modal-content">
            <div class="modal-header">
                <h2>Error</h2>
            </div>
            <div class="modal-body">
                <p>{{session('success')}}</p>
            </div>
        </div>

    </div>
@endif



<div class="container">

    <div class = "imagecontainer">
        <img src="/products/photos/{{$product->filepath}}" class ="resize">
    </div>

    <div class = "infocontainer">
        <h1> {{$product->name}} </h1>
        <p>Type : {{$product->type}}</p>
        @if($product->quantity > 0)
            <b> <p id = "stock">In stock </p></b>

         @else
            <b> <p id = "stock">Out of stock </p></b>
        @endif

        <div class ="productprice">
            <p>Price : {{$product->price}} € </p>
        </div>
    </div>

    <hr>
    <br>
    <div>
        <form id='form' action='/cart' method='POST'>
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <input type="hidden" name="id" value="{{$product->id}}">
        </form>
        @if  (Auth::check() && Auth::user()->role == 'user'|| !Auth::check())

            <button id='cart' value ='{{$product->id}}'class="editsubmitprofile">Add to Cart</button>
            <div id="paypal-button"></div>

             @else
            <button id='cart' value ='{{$product->id}}'class="editsubmitprofile" disabled style="background-color:#a1ada1; cursor:not-allowed;">Add to Cart</button>
            <button class="editsubmitprofile" disabled style="background-color:#a1ada1; cursor:not-allowed;"> Buy Now </button>

        @endif

    </div>
  
    <br>
    <br>
    <div class="fb-comments" data-href="http://127.0.0.1:8000/product/{{$product->id}}" data-numposts="10"></div>
</div>


    



@endsection