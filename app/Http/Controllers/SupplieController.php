<?php

namespace App\Http\Controllers;

use DB;
use App\Models\User;
use App\Models\Supermarket;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Requests\StoreFournisseurRequest;


class SupplieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $data = User::select(\DB::raw("COUNT(*) as count"), \DB::raw("DAYNAME(created_at) as day_name"), \DB::raw("DAY(created_at) as day"))
        ->where('created_at', '>', Carbon::today()->subDay(6))
        ->groupBy('day_name','day')
        ->orderBy('day')
        ->get();
     $array[] = ['Name', 'Number'];
     foreach($data as $key => $value)
     {
       $array[++$key] = [$value->day_name, $value->count];
     }
    //  return $data;

        $supplies = Supermarket::getProductBySupplie();
        if (Auth::user()->role == 'admin') {

            return view('backend.supplies.index')->with('supplies', $supplies);
        } elseif (Auth::user()->role == 'fournisseur') {

            return view('fournisseur.index')->with('users', json_encode($array));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $supermarkets = Supermarket::where('status', 'active')->get();

        return view('backend.supplies.form', [
            'supermarkets' => $supermarkets,
            'supplie' => new User()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFournisseurRequest $request)
    {
        $supplie = User::create([
            'name' => $request->name,
            'email' =>  $request->email,
            'photo' => $request->photo,
            'password' => Hash::make('1111'),
            'role' => '1'
        ]);


        // $supermarket=Supermarket::findOrFail($request->supermarket_id);
        // $supermarket->update([
        //     'user_id' => $supplie->id
        // ]);

        if($supplie){
  
            request()->session();
            Alert::toast('Vous avez ajouté un nouveau fournisseur avec succès !!', 'success');
            return redirect()->route('supplie.index');
        }
        else{
            request()->session();
            Alert::toast('Vous avez ajouté un nouveau fournisseur avec succès !!', 'error');
            return redirect()->route('supplie.index');
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(Fournisseur $fournisseur)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Fournisseur $fournisseur)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFournisseurRequest $request, Fournisseur $fournisseur)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Fournisseur $fournisseur)
    {
        //
    }
}
