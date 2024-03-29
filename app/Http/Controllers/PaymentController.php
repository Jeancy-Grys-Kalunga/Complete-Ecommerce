<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Guysolamour\Cinetpay\Cinetpay;
use Guysolamour\Cinetpay\Http\Controllers\PaymentController as CinetpayPaymentController;

class PaymentController extends CinetpayPaymentController
{
   public function cancel(Request $request)
    {
        // redirect the user where you want
        // return redirect('/'); // or redirect()->home();
    }

    public function return(Request $request, Cinetpay $cinetpay)
    {
        // $cinetpay->getTransactionBuyer();
        // $cinetpay->getTransactionDate()->toDateString();
        // $cinetpay->getTransactionCurrency();
        // $cinetpay->getTransactionPaymentMethod();
        // $cinetpay->getTransactionPaymentId();
        // $cinetpay->getTransactionPhoneNumber();
        // $cinetpay->getTransactionPhonePrefix();
        // $cinetpay->getTransactionLanguage();
        // $cinetpay->isValidPayment();


        if ($cinetpay->isValidPayment()) {
            // success
        } else {
            // fail
        }

        // redirect the user where you want
        // return redirect('/'); // or redirect()->home();
    }

    public function notify(Request $request, Cinetpay $cinetpay)
    {
        // $cinetpay->getTransactionBuyer();
        // $cinetpay->getTransactionDate()->toDateString();
        // $cinetpay->getTransactionCurrency();
        // $cinetpay->getTransactionPaymentMethod();
        // $cinetpay->getTransactionPaymentId();
        // $cinetpay->getTransactionPhoneNumber();
        // $cinetpay->getTransactionPhonePrefix();
        // $cinetpay->getTransactionLanguage();
        // $cinetpay->isValidPayment();


        if ($cinetpay->isValidPayment()){
            // success
        }else {
            // fail
        }

        // redirect the user where you want
        // return redirect('/'); // or redirect()->home();
    }
}
