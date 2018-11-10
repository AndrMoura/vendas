@extends('layouts.app')


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

    #remove_label{
        background-color: #f4825d;
        text-align: center;
        margin: 5px;
    }
    #remove_label:hover {
        background-color: #f4ac86;
    }

</style>

@section('content')
<link href = "{{ asset('css/main.css') }}" rel ="stylesheet">
<link href = "{{ asset('css/profile.css') }}" rel ="stylesheet">
<link href = "{{ asset('css/table.css') }}" rel ="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<script>

    function addProduct(){


       $.ajax({

           url: "http://127.0.0.1:8000/manage/products",
           data: new FormData($("#data")[0]),
           async: false,
           method: 'post',
           processData: false,
           contentType: false,
           error: function (data) {

                var json   = data.responseJSON;

                var errors = json['errors'];

               $("#name_error").text("");
               $("#price_error").text("");
               $("#quantity_error").text("");

               for(var error in errors){

                   if(error == 'name1'){
                       $("#name_error").text(errors[error][0]);
                   }
                   if(error == 'price1'){
                       $("#price_error").text(errors[error][0]);
                   }
                   if(error == 'quantity1'){
                       $("#quantity_error").text(errors[error][0]);
                   }

                }
            }})

            .done(function(json) {

                var product = json;
                var list = $("#table");

                list.append("<td>" + product.id + "</td>");
                list.append("<td>" + product.name + "</td>");
                list.append("<td>" + product.price + "</td>");
                list.append("<td>" + product.type + "</td>");
                list.append("<td>" + product.quantity + "</td>");
                list.append("<td>" + product.supplier_id + "</td>");
                list.append("<td id='remove'><label id='remove_label'>remove</label></td>");


                $('#name1').val('');
                $('#price1').val('');
                $('#type1').val('');
                $('#quantity1').val('');
                $('#supplier1').val('');


            })
    }

    function editProduct(id, dados, colname){

        var dataObject = {};
        dataObject[colname] = dados;


        $.ajax({
            method: "POST",
            url: "http://127.0.0.1:8000/manage/products/edit",
            headers: {"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')},
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                id: id,
                dados: dataObject,
                colname: colname,
            },
            datatype: 'json',
            contentType: 'application/x-www-form-urlencoded',
            error: function (data) {

                var json   = data.responseJSON;

                var errors = json['errors'];

                $("#alert").css('display', 'block')

                for(var error in errors){

                    $("#edit_error").text(errors[error][0]);
                }
            }
        })

    }

    function deleteProduct(id){

        $.ajax({
            method: "POST",
            url: "http://127.0.0.1:8000/manage/products/delete",
            headers: {"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')},
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                id: id
            },
            datatype: 'json',
            contentType: 'application/x-www-form-urlencoded',
            error: function (data) {


            }
        })
            .done(function(json){

                $("[value=" + id + "]").remove();

            })
    }

    $(document).ready(function() {
        $("#alert").click(function () {
            $('#alert').hide("slow");
        });
    });

    $(document).on('blur', '#product_list', function(event){

            event.target.removeAttribute("contentEditable");
            var id = event.target.parentElement.attributes[1].textContent;
            var data = event.target.innerText;
            var col_name = event.target.id;

            editProduct(id, data, col_name);

    });

    $(document).ready(function() {
        $('td').on('click', function() {
            var td = $(this);

           if(td.attr('id') == 'remove'){
                   if(confirm("Are you sure you want to remove this product?")) {
                       var id =  td.parent().attr('value');
                       deleteProduct(id);
                   }
            }
            else {
                td.attr('contenteditable','true');
                td.focus();
            }

        });
    });

    $(document).ready(function() {
        $("#data").submit(function(e) {
            e.preventDefault();
            console.log('chegou aki');
            addProduct();
        }
    )});


</script>

@if($count == 0)
    <p>No products found in the database</p>

@else

<div id="alert" style="display: none">
    <label id="edit_error" ></label>
</div>


<table id="table">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Supplier</th>
        <th>Price</th>
        <th>Type</th>
        <th>Quantity</th>
        <th>Action</th>
      </tr>
    @foreach($products as $product)

      <tr id="product_list" value="{{$product->id}}">
        <td >{{$product->id}} </td>
        <td id="name" >{{$product->name}}</td>
        <td id="supplier_id">{{$product->supplier_id}}</td>
        <td id="price">{{$product->price}}</td>
        <td id="type">{{$product->type}}</td>
        <td id="quantity">{{$product->quantity}}</td>
        <td id="remove"><label id="remove_label">remove</label></td>

      </tr>

@endforeach
</table>
@endif

<form id="data" method="post" enctype="multipart/form-data">
      <div class="displayprofile">
            <h3>Add New Product</h3>

                {{ csrf_field() }}
            <label for="name">Name</label>
            <input type="text" name="name1">
            <p id="name_error"></p>

            <label for="price">Price</label>
            <input type="text" name="price1" >
            <p id="price_error"></p>

              <label for="Type">Type</label>
                  <select name="type1">
                          <option value="pc" >PC</option>
                          <option value="phone" >Phone</option>
                          <option value="others">Others</option>
                  </select>

            <label for="quantity">Quantity</label>
            <input type="text" name="quantity1">
             <p id="quantity_error"></p>

            <label for="supplier">Supplier</label>
            <select name="supplier_id1">
                @foreach($suppliers as $supplier)
                    <option value="{{$supplier->id}}" >{{$supplier->name}}</option>
                @endforeach
            </select>

          <br>

          <input type="file" id="file1" name="image" title="Photo">

      </div>
            <button class="editsubmitprofile"> Add Product </button>
</form>

@endsection
