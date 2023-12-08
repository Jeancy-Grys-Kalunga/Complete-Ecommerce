<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(){

        if(Auth::user()->role == 'fournisseur') {
            return view('fournisseur.notification.index');
        } elseif (Auth::user()->role == 'admin') {
            return view('backend.notification.index');
        }


    }
    public function show(Request $request){
         $notification=Auth()->user()->notifications()->where('id',$request->id)->first();
        if($notification){
            $notification->markAsRead();
            return redirect($notification->data['actionURL']);
        }
    }
    public function delete($id){
        $notification=Notification::find($id);
        if($notification){
            $status=$notification->delete();
            if($status){
                request()->session()->flash('success','Notification supprimée avec succès ');
                return back();
            }
            else{
                request()->session()->flash('error','Quelque chose ce mal passé veuillez réessayer plus tard !!');
                return back();
            }
        }
        else{
            request()->session()->flash('error','Aucune notification trouvée');
            return back();
        }
    }
}
