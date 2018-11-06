@extends('layouts.app')

@section('content')
<link href = "{{ asset('css/main.css') }}" rel ="stylesheet">
<link href = "{{ asset('css/profile.css') }}" rel ="stylesheet">

<form action="/manage/products" method="POST">
      <div class="displayprofile">
            <h3>Add New Product</h3>
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            
            <label for="name">Name</label>
            <input type="text" id="name" name="name">
            @if ($errors->has('name'))
                <strong>{{ $errors->first('name') }}</strong>
            @endif

            <label for="price">Price</label>
            <input type="text" id="price" name="price">
            @if ($errors->has('price'))
                <strong>{{ $errors->first('price') }}</strong>
            @endif

            <label for="type">Type</label>
            <input type="text" id="type" name="type">
            @if ($errors->has('type'))
                <strong>{{ $errors->first('type') }}</strong>
            @endif

            <label for="quantity">Quantity</label>
            <input type="text" id="quantity" name="quantity">
            @if ($errors->has('quantity'))
                <strong>{{ $errors->first('quantity') }}</strong>
            @endif

            <label for="supplier">Supplier</label>
            <select>
                @foreach($suppliers as $supplier)
                    <option value="supplier->id">{{$supplier->name}}</option>
                @endforeach
            </select>


      </div> 
            
            <input type="submit" class="editsubmitprofile" value="Save"> 
      </form>

@endsection