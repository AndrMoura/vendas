@extends('layouts.search')

@section('content')

 <head>
     <title> Homepage</title>
</head>

<link href = "{{ asset('css/main.css') }}" rel ="stylesheet">
<link href = "{{ asset('css/home.css') }}" rel ="stylesheet">
<link href = "{{ asset('css/windowAlert.css') }}" rel ="stylesheet">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous"><link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">


<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    

<script>

$(document).on('click', ".product-card", function(e){

        console.log('teste');
        product = $(this);
        link = product.find("a").attr("href");
        window.open(link, "_self");
        }
);

$(document).on('click', "#button", function(e){

        e.stopPropagation();
        var id = $(this).val();
        addProductCard(id);
    }
);

 $(document).ready(function() {
        $("#alert").click(function () {
            $('#alert').hide("slow");
        });
    });

$(document).ready(function() {

    var checkLogin = "{{{ (Auth::user()) ? Auth::user() : null }}}";
    console.log(checkLogin);
    if(checkLogin){

        $('.cartlabel').text({{Session::get('quantity')}});
    }
    else {

         var coded = "{{Cookie::get('cart') != "" ? Cookie::get('cart') : ""}}";

         if(coded != ""){
             var cart = jQuery.parseJSON($.parseHTML(coded)[0].textContent);
             console.log(cart);
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
</head>

<script>

function addProductCard(id){

    $.ajax({
        method: "POST",
        url: "http://127.0.0.1:8000/home",
        headers: {"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')},
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            id: id
        },
        datatype: 'json',
        xhrFields: { withCredentials: true },
        contentType: 'application/x-www-form-urlencoded',
        error: function (data) {
            var data = data.responseJSON;
           $('.modal').css('display','block');
           $('#modaltext').text(data['error']);
        }

    })

    .done(function(json) {

    var quantity = json;
    console.log(json);


        $('.cartlabel').text(quantity);
        $("#alert").css('display', 'block');
        $('#success').text("Added to the cart");


})
}

$(document).ready(function() {
    $(".modal").click(function () {
        $('.modal').hide("slow");
    });
});

function searchProduct(name){

        $.ajax({
            method: "GET",
            url: "http://127.0.0.1:8000/home/search",
            headers: {"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')},
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                name: name
            },
            datatype: 'json',
            contentType: 'application/x-www-form-urlencoded',
            error: function (data) {

            }
        })

            .done(function(json) {

                var product = json;

                if( $("#test").length){
                    var test = $("#test");
                    test.empty();
                }

                else {
                    var test = $("<div/>");
                    test.attr("id", "test");
                    $('p').remove();

                }

                $(".pagination").remove();

                if(product[0] != undefined){

                    for(pro in product){

                        console.log('entrou aki');
                        var products = $("<div/>");
                        products.attr("class","products");

                        var productcard = $("<div/>");
                        productcard.attr("class", "product-card");

                        var productimage = $("<div/>");
                        productimage.attr("class", "product-image");

                        var img = $('<img>');
                        img.attr( {src: "products/photos/"+product[0].filepath});

                        var productinfo = $("<div/>");
                        productinfo.attr("class","product-info");

                        var h5 = $("<h5/>");
                        h5.text(product[0].name);

                        var h6 = $("<h6/>");
                        h6.text(product[0].price + "€");

                        var button = $("<button/>");

                        var checkUser =  "{{{ (Auth::user()) ? Auth::user() : "" }}}";

                        if(checkUser != ""){
                            checkUser = jQuery.parseJSON($.parseHTML(checkUser)[0].textContent);

                            button.attr("value",product[0].id);
                            button.attr("id","button");
                            button.html("<i class='fas fa-cart-plus'></i>");

                            if(checkUser && checkUser['role'] == 'admin'){
                                button.attr("disabled","true");
                                button.css("background-color", "#a1ada1");
                                button.css("cursor", "not-allowed");
                            }
                        }
                        else {
                            button.attr("value",product[0].id);
                            button.attr("id","button");
                            button.html("<i class='fas fa-cart-plus'></i>");
                        }


                        var link = $("<a/>");
                        link.attr('id', 'link');
                        link.attr('href', "/product/"+product[0].id);
                        productinfo.append(h5);
                        productinfo.append(h6);
                        productinfo.append(button);

                        productimage.append(img);

                        productcard.append(productimage);
                        productcard.append(productinfo);
                        productcard.append(link);

                        products.append(productcard);

                        test.append(products);
                    }
                }
                else {
                    $("#test").html("<p> <b> No products found </b> </p>");
                    }
            })
    }

 $(document).ready(function() {
        $("#textsearch").keypress(function (event) {
            if(event.keyCode == 13)
            {
                var name = $("#textsearch").val();
                searchProduct(name);
            }
        });

    });

</script>


@if($count == 0)
    <b><p>No products found in the database</p></b>

@else
    @if (session()->has('login'))
        <div id="myModal" class="modal">

            <div class="modal-content">
                <div class="modal-header">
                    <h2>Success</h2>
                </div>
                <div class="modal-body">
                    <p id="modaltext">{{session('login')}}</p>
                </div>
            </div>
        </div>
        <div class=" alert-success">{!! session('flash_notification.success') !!}</div>
    @endif

<div id="myModal" class="modal" style="display: none">

    <div class="modal-content">
        <div class="modal-header">
            <h2>Error</h2>
        </div>
        <div class="modal-body">
            <p id="modaltext"></p>
        </div>
    </div>
</div>

<div id="alert" style="display: none">
    <label id="success" ></label>
</div>

<div id ="test">
<div class ="products">
@foreach($products as $product)

<div class="product-card">
    <div class="product-image">
        <img src="/products/photos/{{$product->filepath}}">
    </div>

    <div class="product-info">
        <h5>{{ $product-> name }}</h5>
        <h6>{{ $product-> price }} €</h6>
        @if  (Auth::check() && Auth::user()->role == 'user'|| !Auth::check())
            <button  id="button"  value="{{$product->id}}"><i class="fas fa-cart-plus"></i></button>
        @else
            <button  id="button"  value="{{$product->id}}" disabled style="background-color:#a1ada1; cursor:not-allowed;" ><i class="fas fa-cart-plus"></i></button>
        @endif
    </div>
    <a id="link" href="product/{{$product->id}}"></a>
  </div>
  @endforeach

</div>
</div>

{{$products->links()}}
@endif
@endsection