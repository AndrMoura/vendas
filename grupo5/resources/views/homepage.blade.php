@extends('layouts.search')

@section('content')

<link href = "{{ asset('css/main.css') }}" rel ="stylesheet">
<link href = "{{ asset('css/home.css') }}" rel ="stylesheet">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous"><link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">


<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<script>

$(document).on('click', ".product-card", function(e){

        product = $(this);
        link = product.find("a").attr("href");
        window.open(link, "_self");
        }
);

$(document).on('click', "#button", function(e){

    e.stopPropagation();
    console.log('chegou aki');
        window.open('/cart', "_self");
    }
);

</script>
</head>

<script>

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
                        h6.text(product[0].price);

                        var link = $("<a/>");
                        link.attr('id', 'link');
                        link.attr('href', "/product/"+product[0].id);
                        productinfo.append(h5);
                        productinfo.append(h6);

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

<div id ="test">
<div class ="products">

@foreach($products as $product)
<div class="product-card">
    <div class="product-image">
        <img src="/products/photos/{{$product->filepath}}">
    </div>

    <div class="product-info">
      <h5>{{ $product-> name }}</h5>
      <h6>Price: {{ $product-> price }} €</h6>
        <button id="button"><i class="fas fa-cart-plus"></i></button>
    </div>
    <a id="link" href="product/{{$product->id}}"></a>
  </div>
  @endforeach

</div>
</div>


{{$products->links()}}
@endif
@endsection