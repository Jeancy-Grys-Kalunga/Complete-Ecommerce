<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UpdateShopRequest;
use App\Models\Supermarket;
use App\Models\User;
use Illuminate\Support\Str;

class SuperMarketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $supermarkets = Supermarket::getProductBySupplie();

        return view('backend.supermarket.index', [
            'supermarkets' => $supermarkets,
        ]);
    }

    /**
     * Display a listing of the supermarkets.
     */
    public function markets(Request $request)
    {
        // Récupérer la position de l'utilisateur

        $latitude = -11.6642316;
        $longitude = 27.4826264;

         // Définir le rayon de recherche en kilomètres
         $rayon = 10;

        $supermarkets = Supermarket::geofence($latitude, $longitude, 0, $rayon)->get();
        return view('frontend.pages.supermarkets', [
            'supermarkets' => $supermarkets,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $fournisseurs = User::where('role', 1)->get();

        return view('backend.supermarket.form', [
            'fournisseurs' => $fournisseurs,
            'supermarket' => new Supermarket()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // return $request->all();
        $this->validate($request, [
            'title' => 'string|required',
            'description' => 'string|nullable',
            'thumbail' => 'string|nullable',
            'address' =>  'string|required',
            'user_id' => 'exists:users,id',
            'status' => 'required|in:active,inactive',
        ]);

        $data = $request->all();
        $slug = Str::slug($request->title);

        $data['slug'] = $slug;

        $status = Supermarket::create($data);
        if ($status) {
            request()->session()->flash('success', 'Supermarché ajouté avec succès !!');
        } else {
            request()->session()->flash('error', 'Erreur surveneue lors de l\'enregistrement du Supermarché, Veuillez réessayer plus tard');
        }
        return redirect()->route('supermarket.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Supermarket $supermarket)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supermarket $supermarket)
    {
        $fournisseurs = User::where('role', 1)->get();
 
        return view('backend.supermarket.form', [
            'fournisseurs' => $fournisseurs,
            'supermarket' => $supermarket
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Supermarket $supermarket)
    {
        $this->validate($request, [
            'title' => 'string|required',
            'description' => 'string|nullable',
            'thumbail' => 'string|nullable',
            'address' =>  'string|required',
            'user_id' => 'exists:users,id',
            'status' => 'required|in:active,inactive',
        ]);

        $data = $request->all();
        $slug = Str::slug($request->title);

        $data['slug'] = $slug;

        $status = $supermarket->fill($data)->save();
        if ($status) {
            request()->session()->flash('success', 'La mise à jour des informations du supermarché effectué avec succès !!');
        } else {
            request()->session()->flash('error', 'Erreur surveneue lors de la modification des infos du Supermarché, Veuillez réessayer plus tard');
        }
        return redirect()->route('supermarket.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supermarket $supermarket)
    {

        $status = $supermarket->delete();

        if($status) {
            request()->session()->flash('success', 'Supermarché supprimé avec succès ');
        } else {
            request()->session()->flash('error', 'Erreur survenue lors de la suppression');
        }
        return redirect()->route('supermarket.index');
    }
}
