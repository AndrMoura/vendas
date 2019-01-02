@extends('layouts.search')

@section('content')
<head>
    <title> User cart</title>
</head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<link href = "{{ asset('css/cart.css') }}" rel ="stylesheet">
<link href = "{{ asset('css/main.css') }}" rel ="stylesheet">
<link href = "{{ asset('css/windowAlert.css') }}" rel ="stylesheet">


<script>

    function changeCart(id, quantity){

        $.ajax({
            method: "POST",
            url: "http://127.0.0.1:8000/updateCart",
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                id: id,
                quantity: quantity
            },
            datatype: 'json',
            contentType: 'application/x-www-form-urlencoded',
            error: function (data) {

                var data = data.responseJSON;
                $('.modal').css('display','block');
                $('#modaltext').text(data['error']);
            }
        })
            .done(function(cart){

                $("#"+id).find('#subtotal').html(cart.quantity * cart.price);

                var sum = 0;

                $('.subtotal').each(function(){
                    sum += parseFloat($(this).text());
                });

                $('#cart-total').text(sum);


                var sum = 0;
                $('.input_quantity').each(function(){
                    sum += parseFloat($(this).val());
                });
                console.log(sum);
                $('.cartlabel').text(sum);
        })

    }

    $(document).on('change', '#inputquantity', function(){

      var quantity =  $(this).val();
      var id = $(this).parent().parent().attr('id');
      changeCart(id, quantity);

    });

    function deleteCart(id){

        $.ajax({
            method: "POST",
            url: "http://127.0.0.1:8000/cart/delete/",
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                id: id
            },
            datatype: 'json',
            contentType: 'application/x-www-form-urlencoded',
            error: function (data) {

            }
        })
            .done(function(){

                var productRow =  $("[id=" + id + "]");

                productRow.remove();
                var sum = 0;

                $('.subtotal').each(function(){
                    sum += parseFloat($(this).text());
                });

                $('#cart-total').text(sum);

                var sum = 0;
                $('.input_quantity').each(function(){
                    sum += parseFloat($(this).val());
                });
                $('.cartlabel').text(sum);

            })
    }

    $(document).ready(function() {
        $(".modal").click(function () {
            $('.modal').hide("slow");
        });
    });

    $(document).on('click', 'td', function(){

        var td= $(this);
        if(td.attr('id') == 'remove'){
            if(confirm("Are you sure you want to remove this product?")) {
                var id =  td.parent().attr('id');
                deleteCart(id);
            }
        }

    });


    $(document).ready(function() {

        $("#textsearch").keypress(function (event) {
            if(event.keyCode == 13)
            {
               // location.replace(document.referrer);
                console.log($(this).val());
                searchVal = $(this).val();
                window.open("/home/search?name="+searchVal,"_self");
            }
        });

    });

    $(document).ready(function() {
        var sum = 0;
        $('.input_quantity').each(function(){
            sum += parseFloat($(this).attr('value'));
        });
       $('.cartlabel').text(sum);
    });

</script>
@if((empty($products) && Auth::check()) || empty($products) && !Auth::check() )

    <p style="text-align: center;font-size: 40px;">No items in cart</p>

@else

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

<div class="maxcontainer">
  <div class="shopping-cart">
      
    <table id="table">
      <tr>
          <th>Image</th>
          <th>Product</th>
          <th>Price (EUR)</th>
          <th>Quantity</th>
          <th>Total</th>
          <th>Remove</th>
        </tr>
    @if (Auth::check() && !empty($products))
      @foreach($products as $product)

          <tr id="{{$product->id}}">
          <td ><img id="cartimage" src="/products/photos/{{$product->filepath}}"> </td>
          <td id="name" >{{ $product-> name }}</td>
          <td id="price">{{ $product-> price }}</td>
          <td><input type="number" class="input_quantity" id="inputquantity" value="{{$product->quantity}}" min="1"></td>
          <td id="subtotal" class="subtotal">{{ $product-> price * $product-> quantity }}</td>
          <td id="remove"><label id="remove_label">Remove</label></td>
        </tr>
      @endforeach
    </table>
      @elseif(!Auth::check() && !empty($products))

          @foreach($products as $product)
              <tr id="{{$product->product_id}}">
                  <td ><img id="cartimage" src="/products/photos/{{$product->filepath}}"> </td>
                  <td id="name" >{{ $product-> name }}</td>
                  <td id="price">{{ $product-> price }}</td>
                  <td><input type="number" class="input_quantity" id="inputquantity" value="{{$product->quantity}}" min="1"></td>
                  <td id="subtotal" class="subtotal">{{ $product-> price * $product-> quantity }}</td>
                  <td id="remove"><label id="remove_label">Remove</label></td>
              </tr>
          @endforeach
         </table>
      @endif
  </div>

</div>

<div class="maxcontainer">
    <div class ="lowercartpage">
        <div class="total" style="text-align: center; padding: 20px;">
          <strong>Total price</strong>
          <div class="totals-value" style="display: inline;" id="cart-total">{{$total}}</div> €
            </div>
        </div>
</div>
    <div id="paypal-button" style="position: relative;left: 1307px;"></div>
<script src="https://www.paypalobjects.com/api/checkout.js"></script>


<script>
    paypal.Button.render({
        env: 'sandbox',
        payment: function(data, actions) {

            return actions.request.post('/create-payment', {
                _token: '{{csrf_token() }}',
                checkType: true
            })
                .then(function(res) {

                    console.log(res);
                    if(res.hasOwnProperty('Error')){
                        console.log('entrou aki');
                        alert(res.error);
                    }
                    return res.id;
                })
        },
        onAuthorize: function(data, actions) {

            return actions.request.post('execute-payment', {
                _token: '{{csrf_token() }}',
                paymentID: data.paymentID,
                payerID: data.payerID
            })
                .then(function (res) {
                    console.log(res);
                    $('.maxcontainer').remove();
                    $('#paypal-button').remove();
                    var noItems = $("<p></p>").text("no items in cart.");
                    $("body").append(noItems);
                    $(".cartlabel").text(0);
                })
        }
    }, '#paypal-button');
</script>


@endif
@endsection