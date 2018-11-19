@extends('layouts.search')

@section('content')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<link href = "{{ asset('css/main.css') }}" rel ="stylesheet">
<link href = "{{ asset('css/productpage.css') }}" rel ="stylesheet">

<script>
    $(document).ready(function() {
        $("#cart").click(function () {

                //$('#cart').val();
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
</script>

<script>

$(document).ready(function() {

$('.resize').hover(
    function(){
        originalheight = $('.resize')[0].height;
        originalwidth = $('.resize')[0].width;
        $('.resize').animate({width: (originalwidth * 1.3), height: (originalheight * 1.3)}, 100);
    },
    function(){
       $('.resize').animate({width: originalwidth , height: originalheight}, 100);
    }
);
})

</script>

<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = 'https://connect.facebook.net/pt_PT/sdk.js#xfbml=1&version=v3.2&appId=1148634605302466';
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>


<div class="container">

    <div class = "imagecontainer">
        <img src="/products/photos/{{$product->filepath}}" class ="resize">
    </div>

    <div class = "infocontainer">
        <h1> {{$product->name}} </h1>
        <p>Type : {{$product->type}}</p>
        <p>In stock : {{$product->quantity}}</p>
        <div class ="productprice">
            <p>Price : {{$product->price}} € </p>
        </div>
    </div>

    <div>
        <form id='form' action='/cart' method='POST'>
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <input type="hidden" name="id" value="{{$product->id}}">
        </form>

         <button id='cart' value ='{{$product->id}}'class="editsubmitprofile">Add to Cart</button>
         <button class="editsubmitprofile"> Buy Now </button>
    </div>
  
    <br>
    <br>
    <div class="fb-comments" data-href="http://127.0.0.1:8000/product/{{$product->id}}" data-numposts="10"></div>
</div>


    



@endsection