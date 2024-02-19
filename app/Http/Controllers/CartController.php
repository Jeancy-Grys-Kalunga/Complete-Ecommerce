<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Wishlist;
use App\Models\Cart;
use Illuminate\Support\Str;
use Helper;

class CartController extends Controller
{
    protected $product = null;
    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function addToCart(Request $request)
    {
        // dd($request->all());
        if (empty($request->slug)) {
            return redirect()->back()->with('error', 'Produit non valide');
            
        }
        $product = Product::where('slug', $request->slug)->first();
        // return $product;
        if (empty($product)) {
            return redirect()->back()->with('error', 'Produit non valide');
           
        }

        $already_cart = Cart::where('user_id', auth()->user()->id)->where('order_id', null)->where('product_id', $product->id)->first();
        // return $already_cart;
        if ($already_cart) {
            // dd($already_cart);
            $already_cart->quantity = $already_cart->quantity + 1;
            $already_cart->amount = $product->price + $already_cart->amount;
            // return $already_cart->quantity;
            if ($already_cart->product->stock < $already_cart->quantity || $already_cart->product->stock <= 0) {
                return back()->with('error', 'Stock insuffisant!.');
            }
            $already_cart->save();
        } else {

            $cart = new Cart();
            $cart->user_id = auth()->user()->id;
            $cart->product_id = $product->id;
            $cart->price = ($product->price - ($product->price * $product->discount) / 100);
            $cart->quantity = 1;
            $cart->amount = $cart->price * $cart->quantity;
            if ($cart->product->stock < $cart->quantity || $cart->product->stock <= 0) {
                return back()->with('error', 'Stock insuffisant !');
            }
            $cart->save();
            $wishlist = Wishlist::where('user_id', auth()->user()->id)->where('cart_id', null)->update(['cart_id' => $cart->id]);
        }
        return redirect()->back()->with('success', 'Produit ajouté au panier avec succès ');
    }

    public function singleAddToCart(Request $request)
    {
        $request->validate([
            'slug'      =>  'required',
            'quant'      =>  'required',
        ]);
        // dd($request->quant[1]);


        $product = Product::where('slug', $request->slug)->first();
        if ($product->stock < $request->quant[1]) {
            return back()->with('error', 'Non disponible, Vous pouvez ajouter d\'autres produit.');
        }
        if (($request->quant[1] < 1) || empty($product)) {
            return redirect()->back()->with('error', 'Produit non valide');
        }

        $already_cart = Cart::where('user_id', auth()->user()->id)->where('order_id', null)->where('product_id', $product->id)->first();

        // return $already_cart;

        if ($already_cart) {
            $already_cart->quantity = $already_cart->quantity + $request->quant[1];
            // $already_cart->price = ($product->price * $request->quant[1]) + $already_cart->price ;
            $already_cart->amount = ($product->price * $request->quant[1]) + $already_cart->amount;

            if ($already_cart->product->stock < $already_cart->quantity || $already_cart->product->stock <= 0) {
                return back()->with('error', 'Stock insuffisant !!');
            }

            $already_cart->save();
        } else {

            $cart = new Cart();
            $cart->user_id = auth()->user()->id;
            $cart->product_id = $product->id;
            $cart->price = ($product->price - ($product->price * $product->discount) / 100);
            $cart->quantity = $request->quant[1];
            $cart->amount = ($product->price * $request->quant[1]);
            if ($cart->product->stock < $cart->quantity || $cart->product->stock <= 0) {
                return back()->with('error', 'Stock insuffisant !!.');
            }
            // return $cart;
            $cart->save();
        }
        return redirect()->back()->with('success', 'Produit ajouté avec succès dans le panier.');
    }

    public function cartDelete(Request $request)
    {
        $cart = Cart::find($request->id);
        if ($cart) {
            $cart->delete();
            return redirect()->back()->with('success', 'Produit supprimé du panier avec succès !!');
        }
        return redirect()->back()->with('error', 'Désolé veuillez réessayer plus tard');
    }

    public function cartUpdate(Request $request)
    {
        // dd($request->all());
        if ($request->quant) {
            $error = array();
            $success = '';
            // return $request->quant;
            foreach ($request->quant as $k => $quant) {
                // return $k;
                $id = $request->qty_id[$k];
                // return $id;
                $cart = Cart::find($id);
                // return $cart;
                if ($quant > 0 && $cart) {
                    // return $quant;

                    if ($cart->product->stock < $quant) {
                        return redirect()->back()->with('error', 'Produit Non disponible pour le moment');
                    }
                    $cart->quantity = ($cart->product->stock > $quant) ? $quant : $cart->product->stock;
                    // return $cart;

                    if ($cart->product->stock <= 0) {
                        continue;
                    }
                    $after_price = ($cart->product->price - ($cart->product->price * $cart->product->discount) / 100);
                    $cart->amount = $after_price * $quant;
                    // return $cart->price;
                    $cart->save();
                    $success = 'Votre panier est mis à jour avec succès ';
                } else {
                    $error[] = 'Panier non valide !';
                }
            }
            return back()->with($error)->with('success', $success);
        } else {
            return back()->with('Panier non valide !');
        }
    }

    public function checkout(Request $request)
    {

        return view('frontend.pages.checkout');
    }
}
