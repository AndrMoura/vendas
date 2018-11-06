@extends('layouts.app')

@section('content')
<link href = "{{ asset('css/main.css') }}" rel ="stylesheet">
<link href = "{{ asset('css/profile.css') }}" rel ="stylesheet">
<link href = "{{ asset('css/table.css') }}" rel ="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

@if($count == 0)
    <p>No products found in the database</p>

@else


<script>
    
function addProduct(){

    var token   = $("#myToken").val();
    var name = $('#name').val();
    var price = $('#price').val();
    var type = $("#type").val();
    var quantity = $('#quantity').val();
    var supplier = $('#supplier_id').val();
    console.log(type);

    $.ajax({
                method: "POST",
                url: "http://127.0.0.1:8000/manage/products",
                headers: {"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')},
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    name: name,
                    price: price,
                    type: type,
                    quantity: quantity,
                    supplier_id: supplier
                },
                datatype: 'text',
                contentType: 'application/x-www-form-urlencoded',
                error: function (jqXHR, error, errorThrown) {
                    if (jqXHR.status && jqXHR.status == 422) {
                        //alert(jqXHR.responseText);
                      var  response = JSON.parse(jqXHR.responseText);
                      var errors = response.errors;
                      console.log(errors);

                      for each(error in erros)
                      //console.log(response.errors.price);


                          //console.log(error);
                          //$("#errors").text(response.errors.price[0]);


                    }
                }
            })
        .done(function(json){

            var product = JSON.parse(json);
            var list = $("#table");

            list.append("<td>"+product.id+"</td>");
            list.append("<td>"+product.name+"</td>");
            list.append("<td>"+product.price+"</td>");
            list.append("<td>"+product.type+"</td>");
            list.append("<td>"+product.quantity+"</td>");
            list.append("<td>"+product.supplier_id+"</td>");
            $('#name').val('');
            $('#price').val('');
            $('#type').val('');
            $('#quantity').val('');
            $('#supplier').val('');
        })

}
</script>


<table id="table">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Supplier</th>
        <th>Price</th>
        <th>Type</th>
        <th>Quantity</th>
      </tr>
    @foreach($products as $product)

      <tr id="product_list">
        <td contenteditable="true">{{$product->id}} </td>
        <td contenteditable="true">{{$product->name}}</td>
        <td contenteditable="true">{{$product->supplier_id}}</td>
        <td contenteditable="true">{{$product->price}}</td>
        <td contenteditable="true">{{$product->type}}</td>
        <td contenteditable="true">{{$product->quantity}}</td>
      </tr>

@endforeach
</table>
@endif



<!--<form action="/manage/products" method="POST">-->
      <div class="displayprofile">
            <h3>Add New Product</h3>
            <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
            
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

          <label for="Type">Type</label>
          <select id="type">
                  <option value="pc" >PC</option>
                  <option value="phone" >Phone</option>
                  <option value="others">Others</option>
          </select>

            <label for="quantity">Quantity</label>
            <input type="text" id="quantity" name="quantity">
            @if ($errors->has('quantity'))
                <strong>{{ $errors->first('quantity') }}</strong>
            @endif

            <label for="supplier">Supplier</label>
            <select id="supplier_id">
                @foreach($suppliers as $supplier)
                    <option value="{{$supplier->id}}" >{{$supplier->name}}</option>
                @endforeach
            </select>

          <label id="errors"></label>


      </div> 
            <button onclick="addProduct()" class="editsubmitprofile"> Add Product </button>
            
     <!-- </form>-->

@endsection
