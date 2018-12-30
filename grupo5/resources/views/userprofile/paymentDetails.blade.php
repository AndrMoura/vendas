@extends('layouts.app')

@section('content')

    <head>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <link href = "{{ asset('css/table.css') }}" rel ="stylesheet">
    </head>


    <script>

        $(document).ready(function(){
            $(".button").click(function () {
                var teste = "{{json_encode($pay)}}";
                var payId = jQuery.parseJSON($.parseHTML(teste)[0].textContent);

                console.log(payId['id']);
                window.open('/pdf/?payment=' +payId['id'], "_self");
            });
        });

    </script>

    <table>
        <tr>
            <th colspan="7">Payment Details</th>
        </tr>
        <tr>
            <td>Payment ID</td>
            <td colspan="6">{{$pay->id}}</td>
        </tr>
        <tr>
            <td>Payment Method</td>
            <td colspan="6">{{$pay->payer->payment_method}}</td>
        </tr>
        <tr>
            <td>Cart Number</td>
            <td colspan="6">{{$pay->cart}}</td>
        </tr>
        <tr>
            <td rowspan="2">Payer Information<br></td>
            <td>Email</td>
            <td>First Name<br></td>
            <td>Last Name<br></td>
            <td>Payer ID</td>
            <td>Shipping Address</td>
            <td>Phone</td>
        </tr>
        <tr>
            <td>{{$pay->payer->payer_info->email}}</td>
            <td>{{$pay->payer->payer_info->first_name}}</td>
            <td>{{$pay->payer->payer_info->last_name}}</td>
            <td>{{$pay->payer->payer_info->payer_id}}</td>
            <td>None</td>
            <td>{{Auth::user()->phone}}</td>
        </tr>
        <tr>
            <td rowspan="2">Transactions Details</td>
            <td>total</td>
            <td>currency</td>
            <td colspan="4">subtotal</td>
        </tr>
        <tr>
            <td>{{$pay->transactions[0]->amount->total}}</td>
            <td>{{$pay->transactions[0]->amount->currency}}</td>
            <td colspan="4">{{$pay->transactions[0]->amount->details->subtotal}}</td>
        </tr>
        <tr>
            <td rowspan="2">Items purchased</td>
            <td>Name<br></td>
            <td>sku</td>
            <td>price</td>
            <td>currency</td>
            <td>tax</td>
            <td>quantity</td>
        </tr>
        @foreach($pay->transactions[0]->item_list->items as $item )
        <tr>
            <td>{{$item->name}}</td>
            <td>{{$item->sku}}</td>
            <td>{{$item->price}}</td>
            <td>{{$item->currency}}</td>
            <td>{{$item->tax}}</td>
            <td>{{$item->quantity}}</td>
        </tr>
        @endforeach
        <tr>
            <td>Invoice Number</td>
            <td colspan="6">{{$pay->transactions[0]->invoice_number}}</td>
        </tr>
    </table>
    <input type="button" class="button" value="View in PDF">


@endsection