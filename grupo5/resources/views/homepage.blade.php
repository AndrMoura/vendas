@extends('layouts.app')

@section('content')

<link href = "{{ asset('css/main.css') }}" rel ="stylesheet">
<link href = "{{ asset('css/home.css') }}" rel ="stylesheet">


<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>

@if($count == 0)
    <b><p>No products found in the database</p><b>

@else

<div class ="products">

@foreach($products as $product)
<div class="product-card">
    <div class="product-image">
        <img src="/products/photos/{{$product->filepath}}">
    </div>
    <div class="product-info">
      <h5>{{ $product-> name }}</h5>
      <h6><label>Price: </label>{{ $product-> price }}</h6>
    </div>
  </div>
  @endforeach

  
   {{$products->links()}}

</div>
@endif
@endsection