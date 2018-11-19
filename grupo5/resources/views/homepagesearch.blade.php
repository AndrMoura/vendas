@extends('layouts.search')

@section('content')

<link href = "{{ asset('css/main.css') }}" rel ="stylesheet">
<link href = "{{ asset('css/home.css') }}" rel ="stylesheet">


<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<script>

$(document).on('click', ".product-card", function(e){

        product = $(this);
        link = product.find("a").attr("href");
        window.open(link, "_self");
    }
);

</script>
</head>

<script>

function searchProduct1(name){

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
                    $("#test").empty();
                }

                else {
                    var test = $("<div/>");
                    test.attr("id", "test");
                    $('p').remove();

                }
                var products = $("<div/>");
                products.attr("class","products");

                $(".pagination").remove();

                if(product[0] != undefined){

                    for(pro in product){

                        var productcard = $("<div/>");
                        productcard.attr("class", "product-card");

                        var productimage = $("<div/>");
                        productimage.attr("class", "product-image");

                        var img = $('<img>');
                        img.attr( {src: "/products/photos/"+product[0].filepath});

                        var productinfo = $("<div/>");
                        productinfo.attr("class","product-info");

                        var h5 = $("<h5/>");
                        h5.text(product[0].name);
                        console.log(product[0].name);
                        var h6 = $("<h6/>");
                        h6.text(product[0].price);
                        console.log(product[0].price);




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
                    }
                }
                else {
                   test.html("<p> No products found!! </p>");
                   console.log('echou');

                }
                test.append(products);
                $('main').append(test);
            })
    }

 $(document).ready(function() {
        $("#textsearch").keypress(function (event) {
            if(event.keyCode == 13)
            {
                console.log('teste1');
                var name = $("#textsearch").val();
                searchProduct1(name);
            }
        });

    });
</script>


@if($count == 0)
    <p>No products found in the database</p>
@else

<div id ="test">
<div class ="products">

@foreach($product as $pro)
<div class="product-card">
    <div class="product-image">
        <img src="/products/photos/{{$pro->filepath}}">
    </div>

    <div class="product-info">
      <h5>{{$pro->name}}</h5>
      <h6>Price: {{$pro->price}} €</h6>
    </div>
    <a id="link" href={{ route('produtos', [$pro->id]) }}></a>
  </div>
  @endforeach

</div>
</div>


@endif

@endsection