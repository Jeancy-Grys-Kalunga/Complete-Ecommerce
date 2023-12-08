<?php

namespace App\Http\Controllers;

use Exception;

use App\Http\Cinetpay\CinetPay;
use App\Http\Cinetpay\Commande;



class CinetpayController extends Controller
{
    public function payment(array $datas)
    {

        /*Commenter ses deux lines si vous êtes en production
        error_reporting(E_ALL);
        ini_set('display_errors', 1);*/


        // required libs

        // La class gère la table "Commande"( A titre d'exemple)
        $commande = new Commande();
        try {
            if ($datas) {

                $customer_name = $datas['first_name'];
                $customer_surname = $datas['last_name'];
                $description = 'Paiement de la commande ' . $datas['order_number'];
                $amount = $datas['total_amount'];
                $currency = 'CDF';

                //transaction id
            $id_transaction = date("YmdHis"); // or $id_transaction = Cinetpay::generateTransId()

            //Veuillez entrer votre apiKey
            $apikey =  env('CINETPAY_API_KEY');
            //Veuillez entrer votre siteId
            $site_id = env('CINETPAY_SITE_ID');

            //notify url
            $notify_url = $commande->getCurrentUrl() . 'cinetpay-sdk-php/notify/notify.php';
            //return url
            $return_url = $commande->getCurrentUrl() . 'cinetpay-sdk-php/return/return.php';
            $channels = "ALL";

            /*information supplémentaire que vous voulez afficher
             sur la facture de CinetPay(Supporte trois variables
             que vous nommez à votre convenance)*/
            $invoice_data = array(
                "Data 1" => "",
                "Data 2" => "",
                "Data 3" => ""
            );

            //
            $formData = array(
                "transaction_id" => $id_transaction,
                "amount" => $amount,
                "currency" => $currency,
                "customer_surname" => $customer_name,
                "customer_name" => $customer_surname,
                "description" => $description,
                "notify_url" => $notify_url,
                "return_url" => $return_url,
                "channels" => $channels,
                "invoice_data" => $invoice_data,
                //pour afficher le paiement par carte de credit
                "customer_email" => "", //l'email du client
                "customer_phone_number" => "", //Le numéro de téléphone du client
                "customer_address" => "", //l'adresse du client
                "customer_city" => "", // ville du client
                "customer_country" => "", //Le pays du client, la valeur à envoyer est le code ISO du pays (code à deux chiffre) ex : CI, BF, US, CA, FR
                "customer_state" => "", //L’état dans de la quel se trouve le client. Cette valeur est obligatoire si le client se trouve au États Unis d’Amérique (US) ou au Canada (CA)
                "customer_zip_code" => "" //Le code postal du client
            );
            // enregistrer la transaction dans votre base de donnée
            /*  $commande->create(); */
            dd($formDat,$site_id, $apikey);

            $CinetPay = new CinetPay($site_id, $apikey, $VerifySsl = false); //$VerifySsl=true <=> Pour activerr la verification ssl sur curl
            $result = $CinetPay->generatePaymentLink($formData);

            if ($result["code"] == '201') {
                $url = $result["data"]["payment_url"];

                // ajouter le token à la transaction enregistré
                /* $commande->update(); */
                //redirection vers l'url de paiement
                header('Location:' . $url);
            }
            } else {
                request()->session()->flash('error', 'Veuillez remplir tous les champs obligatoire pour éffectuer cette commande !');
             return back();
            }
            
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}
