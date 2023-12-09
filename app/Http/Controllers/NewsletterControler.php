<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\UserSubscribed;

class NewsletterControler extends Controller
{
    public function subscribe(Request $request)
    {
         $request->validate([
            'email' =>'required|unique|:newsletters,email'
         ]);

         event(new UserSubscribed($request->input('email')));
         request()->session()->flash('success', 'Vous êtes enregistré à notre newsletter avec succès vérifier votre boîte émail !!');
         return back();
    }
}
