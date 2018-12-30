<?php

namespace App\Http\Controllers;
require __DIR__ . '/../../../vendor/paypal/rest-api-sdk-php/sample/bootstrap.php';

use PayPal\Api\Payment;
use Auth;
use Illuminate\Http\Request;
use PDF;

class PDFController extends Controller
{
    public function getPDF(Request $request){

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($this->convertHTML($request));
        return $pdf->stream();
    }


    public function getSalesId($request){

        $apiContext = new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential(
                'AV0DLSMqeLgryVXlRrKRfVmR-jPqxNWs8zHKfbuT262CRHQzUvr8H8lNvWBqb0inM82PB7Wb8VHmSvK6',     // ClientID
                'EO8jAoAUH177f7cKA2xgMQNzQEQy1_HHSn-3vasQ-m986j2vHSbGQPM95CGh2e7l2-LeLOMSa_Y3cqWJ'      // ClientSecret
            )
        );

        try {
            $pay =  Payment::get($request, $apiContext);
        } catch (Exception $ex) {
            exit(1);
        }

        return $pay;

    }

    function convertHTML($request)
    {
        $pay = $this->getSalesId($request->payment);
        $size = count($pay->transactions[0]->item_list->items);
        $size = intval($size);
        $size = $size +1;
        $output = '<table width="100%" style="border-collapse: collapse; border: 1px;">
        <tr>
            <th style="border: 1px solid;" colspan="7">Payment Details</th>
        </tr>
        <tr>
            <td style="border: 1px solid;">Payment ID</td>
            <td style="border: 1px solid;" colspan="6">'.$pay->id.'</td>
        </tr>
        <tr>
            <td style="border: 1px solid;">Payment Method</td>
            <td style="border: 1px solid;" colspan="6">'.$pay->payer->payment_method.'</td>
        </tr>
        <tr>
            <td style="border: 1px solid;">Cart Number</td>
            <td style="border: 1px solid;" colspan="6">'.$pay->cart.'</td>
        </tr>
        <tr>
            <td style="border: 1px solid;" rowspan="2">Payer Information<br></td>
            <td style="border: 1px solid;">Email</td>
            <td style="border: 1px solid;">First Name<br></td>
            <td style="border: 1px solid;">Last Name<br></td>
            <td style="border: 1px solid;">Payer ID</td>
            <td style="border: 1px solid;">Shipping Address</td>
            <td style="border: 1px solid;">Phone</td>
        </tr>
        <tr>
            <td style="border: 1px solid;">'.$pay->payer->payer_info->email.'</td>
            <td style="border: 1px solid;">'.$pay->payer->payer_info->first_name.'</td>
            <td style="border: 1px solid;">'.$pay->payer->payer_info->last_name.'</td>
            <td style="border: 1px solid;">'.$pay->payer->payer_info->payer_id.'</td>
            <td style="border: 1px solid;">None</td>
            <td style="border: 1px solid;">'.Auth::user()->phone.'</td>
        </tr>
        <tr>
            <td style="border: 1px solid;" rowspan="2">Transactions Details</td>
            <td style="border: 1px solid;" >total</td>
            <td style="border: 1px solid;" >currency</td>
            <td style="border: 1px solid;" colspan="4">subtotal</td>
        </tr>
        <tr>
            <td style="border: 1px solid;" >'.$pay->transactions[0]->amount->total.'</td>
            <td style="border: 1px solid;">'.$pay->transactions[0]->amount->currency.'</td>
            <td style="border: 1px solid;" colspan="4">'.$pay->transactions[0]->amount->details->subtotal.'</td>
        </tr>
        <tr>
            <td style="border: 1px solid;" rowspan= '.$size.' >Items purchased</td>
            <td style="border: 1px solid;">Name<br></td>
            <td style="border: 1px solid;" >sku</td>
            <td style="border: 1px solid;" >price</td>
            <td style="border: 1px solid;" >currency</td>
            <td style="border: 1px solid;" >tax</td>
            <td style="border: 1px solid;" >quantity</td>
        </tr>';
        foreach($pay->transactions[0]->item_list->items as $item )
        {
            $output .= '
       <tr>
            <td style="border: 1px solid;">'.$item->name.'</td>
            <td style="border: 1px solid;" >'.$item->sku.'</td>
            <td style="border: 1px solid;" >'.$item->price.'</td>
            <td style="border: 1px solid;" >'.$item->currency.'</td>
            <td style="border: 1px solid;">'.$item->tax.'</td>
            <td style="border: 1px solid;">'.$item->quantity.'</td>
        </tr>
      ';

        }
        $output .= ' <tr>
            <td style="border: 1px solid;">Invoice Number</td>
            <td style="border: 1px solid;" colspan="6">'.$pay->transactions[0]->invoice_number.'</td>
        </tr>
    </table>';

        return $output;
    }
}
