<?php

namespace App\Http\Controllers;

use PayPal\Api\Item;
use PayPal\Api\Payer;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Payment;
use PayPal\Api\ItemList;
use PayPal\Api\WebProfile;
use PayPal\Api\InputFields;
use PayPal\Api\Transaction;
use PayPal\Api\RedirectUrls;
use PayPal\Api\PaymentExecution;

use PayPal\Api\Sale;

use App\Product;
use App\Cart;
use App\User;
use App\Order;

use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

require __DIR__ . '/../../../vendor/paypal/rest-api-sdk-php/sample/bootstrap.php';


class PaymentController extends Controller
{
    public function paymentOptions($products){

         $apiContext = new \PayPal\Rest\ApiContext(
             new \PayPal\Auth\OAuthTokenCredential(
                 'AV0DLSMqeLgryVXlRrKRfVmR-jPqxNWs8zHKfbuT262CRHQzUvr8H8lNvWBqb0inM82PB7Wb8VHmSvK6',     // ClientID
                 'EO8jAoAUH177f7cKA2xgMQNzQEQy1_HHSn-3vasQ-m986j2vHSbGQPM95CGh2e7l2-LeLOMSa_Y3cqWJ'      // ClientSecret
             )
         );

        $payer = new Payer();
        $payer->setPaymentMethod("paypal");

        $iterator = 0;
        $itemArray = array();
        $total = 0;

       foreach ($products as $product) {

            ${"item" . $iterator} =  new Item();
            ${"item" . $iterator}->setName($product->name)
                ->setCurrency('USD')
                ->setQuantity($product->quantity)
                ->setSku($product->product_id)
                ->setPrice($product->price);
            array_push($itemArray, ${"item" . $iterator});
            $total += $product->price * $product->quantity;

        }

        $itemList = new ItemList();

        $itemList->setItems($itemArray);


        $details = new Details();
        $details->setSubtotal($total);

        $amount = new Amount();
        $amount->setCurrency("USD")
            ->setTotal($total)
            ->setDetails($details);
        $transaction = new Transaction();


        $transaction->setAmount($amount)
            ->setItemList($itemList)
            ->setDescription("Payment description")
            ->setInvoiceNumber(uniqid());

        $redirectUrls = new RedirectUrls();
         $redirectUrls->setReturnUrl("http://laravel-paypal-example.test")
        ->setCancelUrl("http://laravel-paypal-example.test");

        // Add NO SHIPPING OPTION
        $inputFields = new InputFields();
        $inputFields->setNoShipping(1);
        $webProfile = new WebProfile();
        $webProfile->setName('test' . uniqid())->setInputFields($inputFields);
        $webProfileId = $webProfile->create($apiContext)->getId();

        $payment = new Payment();
        $payment->setExperienceProfileId($webProfileId); // no shipping
        $payment->setIntent("sale")
            ->setPayer($payer)
            ->setRedirectUrls($redirectUrls)
            ->setTransactions(array($transaction));

        try {
            $payment->create($apiContext);
        } catch (Exception $ex) {
            echo $ex;
            exit(1);
        }


        return $payment;
     }

    public function createPayment(Request $request)
    {
        if(Auth::user()) {

            if ($request->checkType) {
               /*$producttoBuy = Product::find($request->id);

                if ($producttoBuy->quantity <= 0) {
                    return response()->json(['error' => 'No products avaiable']);
                } else {
                    $cart = Cart::all()->where('user_id', Auth::user());
                    return $this->paymentOptions($cart);

                }*/

                $cart = Cart::all()->where('user_id', Auth::user()->id);

                return $this->paymentOptions($cart);
            }

        }
    }

    public function executePayment(Request $request)
        {
            $apiContext = new \PayPal\Rest\ApiContext(
                new \PayPal\Auth\OAuthTokenCredential(
                    'AV0DLSMqeLgryVXlRrKRfVmR-jPqxNWs8zHKfbuT262CRHQzUvr8H8lNvWBqb0inM82PB7Wb8VHmSvK6',     // ClientID
                    'EO8jAoAUH177f7cKA2xgMQNzQEQy1_HHSn-3vasQ-m986j2vHSbGQPM95CGh2e7l2-LeLOMSa_Y3cqWJ'      // ClientSecret
                )
            );
            $paymentId = $request->paymentID;
            $payment = Payment::get($paymentId, $apiContext);
            $execution = new PaymentExecution();
            $execution->setPayerId($request->payerID);

            try {
                $result = $payment->execute($execution, $apiContext);
            } catch (Exception $ex) {
                echo $ex;
                exit(1);
            }
            $transactions = $payment->getTransactions();
            $relatedResources = $transactions[0]->getRelatedResources();
            $sale = $relatedResources[0]->getSale();
            $saleId = $sale->getId();
            echo $saleId;

            $this->registerOrderDB($result);
            return $result;
        }

    public function registerOrderDB($result){

        $item_list = $result->transactions[0]->item_list->items;

        $user = Auth::user();
        $order = new Order();
        $order->user_id = $user->id;
        $user->orders()->save($order);

        for($i = 0; $i < count($item_list); $i++){

            $order->products()->attach($item_list[$i]->sku, ['quantity' => $item_list[$i]->quantity, 'unit_price' => $item_list[$i]->price]);
            $productRemoveQuantity = Product::where('id', $item_list[$i]->sku)->first();
            $productRemoveQuantity->quantity -= $item_list[$i]->quantity;
            $productRemoveQuantity->save();
        }
        session(['quantity' => 0]);
        $cart = Cart::where('user_id', $user->id)->delete();

        }
    }

