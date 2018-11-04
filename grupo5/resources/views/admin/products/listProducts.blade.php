@extends('layouts.app')

@section('content')
<link href = "{{ asset('css/main.css') }}" rel ="stylesheet">


@if($count == 0)
    <p>No products found in the database</p>

@else
<ol>
@foreach($products as $product)
 
     <label>{{ $product->name }}</label>
     <li> <a id="listuserid"  href="/manage/products/edit/{{$product->id}}":>Edit</a></li>

@endforeach
</ol>
@endif

<a id=""  href="/manage/products/add">Add Product</a> 



@endsection
