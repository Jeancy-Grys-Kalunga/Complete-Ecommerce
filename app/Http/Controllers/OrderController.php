<?php

namespace App\Http\Controllers;

use PDF;
use Helper;
use Exception;
use Notification;
use App\Models\Cart;
use App\Models\User;
use App\Models\Order;
use App\Models\Shipping;
use App\Models\Supermarket;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Cinetpay\CinetPay;
use App\Http\Cinetpay\Commande;
use Illuminate\Support\Facades\Auth;
use App\Notifications\StatusNotification;
use App\Notifications\NewOrderNotification;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->role == 'admin') {
            $orders = Order::orderBy('id', 'DESC')->paginate(10);
            return view('backend.order.index')->with('orders', $orders);
        } elseif (Auth::user()->role == 'fournisseur') {
            $orders = Order::orderBy('id', 'DESC')->paginate(10);
            return view('fournisseur.order.index')->with('orders', $orders);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'string|required',
            'last_name' => 'string|required',
            'address1' => 'string|required',
            'address2' => 'string|nullable',
            'coupon' => 'nullable|numeric',
            'phone' => 'numeric|required',
            'post_code' => 'string|nullable',
            'email' => 'string|required',
        ]);

        // return $request->all();

        if (empty(Cart::where('user_id', auth()->user()->id)->where('order_id', null)->first())) {
            request()->session()->flash('error', 'Votre panier est vide !');
            return back();
        }

        $order = new Order();
        $order_data = $request->all();
        $order_data['order_number'] = 'ORD-' . strtoupper(Str::random(10));
        $order_data['user_id'] = $request->user()->id;

        $order_data['shipping_id'] = $request->shipping;
        $shipping = Shipping::where('id', $order_data['shipping_id'])->pluck('price');
        // return session('coupon')['value'];

        $order_data['sub_total'] = Helper::totalCartPrice();
        $order_data['quantity'] = Helper::cartCount();
        if (session('coupon')) {
            $order_data['coupon'] = session('coupon')['value'];
        }
        if ($request->shipping) {
            if (session('coupon')) {
                $order_data['total_amount'] = Helper::totalCartPrice() + $shipping[0] - session('coupon')['value'];
            } else {
                $order_data['total_amount'] = Helper::totalCartPrice() + $shipping[0];
            }
        } else {
            if (session('coupon')) {
                $order_data['total_amount'] = Helper::totalCartPrice() - session('coupon')['value'];
            } else {
                $order_data['total_amount'] = Helper::totalCartPrice();
            }
        }
        // return $order_data['total_amount'];
        $order_data['status'] = "new";

        if (request('payment_method') == 'mobile') {

            // La class gère la table "Commande"( A titre d'exemple)
            $commande = new Commande();
            try {
                if ($order_data) {

                    $customer_name = $request->first_name;
                    $customer_surname = $request->last_name;
                    $description = 'Paiement de la commande ' . $order_data['order_number'];
                    $amount = $order_data['total_amount'];
                    $currency = 'CDF';

                    //transaction id
                    $id_transaction = date("YmdHis"); // or $id_transaction = Cinetpay::generateTransId()

                    //Veuillez entrer votre apiKey
                    $apikey = env('CINETPAY_API_KEY');
                    //Veuillez entrer votre siteId
                    $site_id = env('CINETPAY_SITE_ID');

                    //notify url
                    $notify_url = $commande->getCurrentUrl() . 'payment/success';
                    //return url
                    $return_url = $commande->getCurrentUrl() . 'cancel';
                    $channels = "ALL";

                    /*information supplémentaire que vous voulez afficher
                    sur la facture de CinetPay(Supporte trois variables
                    que vous nommez à votre convenance)*/
                    $invoice_data = array(
                        "Data 1" => "",
                        "Data 2" => "",
                        "Data 3" => "",
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
                        "customer_email" => $request->email, //l'email du client
                        "customer_phone_number" => $request->phone, //Le numéro de téléphone du client
                        "customer_address" => $request->address1,
                        "customer_city" => "", // ville du client
                        "customer_country" => "", //Le pays du client, la valeur à envoyer est le code ISO du pays (code à deux chiffre) ex : CI, BF, US, CA, FR
                        "customer_state" => "", //L’état dans de la quel se trouve le client. Cette valeur est obligatoire si le client se trouve au États Unis d’Amérique (US) ou au Canada (CA)
                        "customer_zip_code" => "", //Le code postal du client
                    );
                    // enregistrer la transaction dans votre base de donnée
                    /*  $commande->create(); */

                    $CinetPay = new CinetPay($site_id, $apikey, $VerifySsl = false); //$VerifySsl=true <=> Pour activerr la verification ssl sur curl
                    $result = $CinetPay->generatePaymentLink($formData);

                    if ($result["code"] == '201') {

                        // ajouter le token à la transaction enregistré
                        /* $commande->update(); */

                    $order_data['payment_method'] = 'mobile';
                    $order_data['payment_status'] = 'paid';
                    $order->fill($order_data);
                    $status = $order->save();

                    if ($order) {
                        $cartproducts = Helper::getAllProductFromCart();
                        foreach($cartproducts as $data){
                            $supplie = Supermarket::supplies($data->product['supermarket_id']);
                            foreach($supplie as $supplie_data){
                                $user_id = $supplie_data->user_id;
                           }
                    $user=User::where('id', $user_id)->get();
                   
                    $details = [
                        'title' => "Vous avez une nouvelle commande de " .  $data->quantity. "  " . $data->product['title'] . " de ". number_format($data->price,2) ." FC",
                        'actionURL' => route('order.show', $order->id),
                        'fas' => 'fa-file-alt'
                    ];
                    Notification::send($user, new StatusNotification($details));
                  }

               }else{
                     session()->forget('cart');
                    session()->forget('coupon');
                   }

                Cart::where('user_id', auth()->user()->id)->where('order_id', null)->update(['order_id' => $order->id]);
                request()->session()->flash('success', 'Commande enregistrée avec succès !!! ');
                return redirect()->away($result["data"]["payment_url"]);
                    }
                } else {
                    request()->session()->flash('error', 'Veuillez remplir tous les champs obligatoire pour éffectuer cette commande !');
                    return back();
                }
            } catch (Exception $e) {
                request()->session() ->flash('error', 'Erreur survenue lors du paiement avec le mobile money !!!');
                return back();
            }
        } else {

            $order_data['payment_method'] = 'cod';
            $order_data['payment_status'] = 'Unpaid';
            $order->fill($order_data);
            $status = $order->save();

            if ($order) {

                $cartproducts = Helper::getAllProductFromCart();
                foreach($cartproducts as $data){
                    $supplie = Supermarket::supplies($data->product['supermarket_id']);
                    foreach($supplie as $supplie_data){
                    $user_id = $supplie_data->user_id;
                    }
                    $user=User::where('id', $user_id)->get();
                   
                    $details = [
                        'title' => "Vous avez une nouvelle commande de " .  $data->quantity. "  " . $data->product['title'] . " de ". number_format($data->price,2) ." FC",
                        'actionURL' => route('order.show', $order->id),
                        'fas' => 'fa-file-alt'
                    ];
                    Notification::send($user, new StatusNotification($details));
                  }

            }else{
                session()->forget('cart');
                session()->forget('coupon');
            }

            Cart::where('user_id', auth()->user()->id)->where('order_id', null)->update(['order_id' => $order->id]);
            request()->session()->flash('success', 'Commande enregistrée avec succès !!! ');
            return redirect()->route('home');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = Order::find($id);
        if (Auth::user()->role == 'admin') {

            return view('backend.order.show')->with('order', $order);
        } elseif (Auth::user()->role == 'fournisseur') {
            $orders = Order::orderBy('id', 'DESC')->paginate(10);
            return view('fournisseur.order.show')->with('order', $order);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $order = Order::find($id);
        return view('fournisseur.order.edit')->with('order', $order);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $order = Order::find($id);
        $this->validate($request, [
            'status' => 'required|in:new,process,delivered,cancel',
        ]);
        $data = $request->all();
        // return $request->status;
        if ($request->status == 'delivered') {
            foreach ($order->cart as $cart) {
                $product = $cart->product;
                // return $product;
                $product->stock -= $cart->quantity;
                $product->save();
            }
        }
        $status = $order->fill($data)->save();
        if ($status) {
            request()->session()->flash('success', 'Commande mis à jour avec succès');
        } else {
            request()->session()->flash('error', 'Quelque chose ce mal passé veuillez réessayer plus tard !!');
        }
        return redirect()->route('order.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order = Order::find($id);
        if ($order) {
            $status = $order->delete();
            if ($status) {
                request()->session()->flash('success', 'Commande supprimé avec succès');
            } else {
                request()->session()->flash('error', 'Cette commende ne peut pas être supprimer !!!');
            }
            return redirect()->route('order.index');
        } else {
            request()->session()->flash('error', 'Cette commande ne peut pas trouver');
            return redirect()->back();
        }
    }

    public function orderTrack()
    {
        return view('frontend.pages.order-track');
    }

    public function productTrackOrder(Request $request)
    {
        // return $request->all();
        $order = Order::where('user_id', auth()->user()->id)->where('order_number', $request->order_number)->first();
        if ($order) {
            if ($order->status == "new") {
                request()->session()->flash('success', 'Votre commande est placée. veuillez patienter.');
                return redirect()->route('home');
            } elseif ($order->status == "process") {
                request()->session()->flash('success', 'Votre commande est en cours. veuillez patienter.');
                return redirect()->route('home');
            } elseif ($order->status == "delivered") {
                request()->session()->flash('success', 'Votre commande est déjà livrée. veuillez patienter.');
                return redirect()->route('home');
            } else {
                request()->session()->flash('error', 'Votre commande est supprimé. veuillez patienter réessayer plus tard');
                return redirect()->route('home');
            }
        } else {
            request()->session()->flash('error', 'Le numéro de la commande saisie est incorrect, veuillez vérifier puis réessayer !!');
            return back();
        }
    }

    // PDF generate
    public function pdf(Request $request)
    {
        $order = Order::getAllOrder($request->id);
        // return $order;
        $file_name = $order->order_number . '-' . $order->first_name . '.pdf';
        // return $file_name;
        $pdf = PDF::loadview('fournisseur.order.pdf', compact('order'));
        return $pdf->download($file_name);
    }
    // Income chart
    public function incomeChart(Request $request)
    {
        $year = \Carbon\Carbon::now()->year;
        // dd($year);
        $items = Order::with(['cart_info'])->whereYear('created_at', $year)->where('status', 'delivered')->get()
            ->groupBy(function ($d) {
                return \Carbon\Carbon::parse($d->created_at)->format('m');
            });
        // dd($items);
        $result = [];
        foreach ($items as $month => $item_collections) {
            foreach ($item_collections as $item) {
                $amount = $item->cart_info->sum('amount');
                // dd($amount);
                $m = intval($month);
                // return $m;
                isset($result[$m]) ? $result[$m] += $amount : $result[$m] = $amount;
            }
        }
        $data = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthName = date('F', mktime(0, 0, 0, $i, 1));
            $data[$monthName] = (!empty($result[$i])) ? number_format((float) ($result[$i]), 2, '.', '') : 0.0;
        }
        return $data;
    }
}
