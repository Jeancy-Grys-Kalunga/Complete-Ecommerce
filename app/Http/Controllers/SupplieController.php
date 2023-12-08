<?php

namespace App\Http\Controllers;

use DB;
use App\Models\User;
use App\Models\Supermarket;
use Illuminate\Support\Carbon;


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
        
        return view('fournisseur.index')->with('users', json_encode($array));
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
        //
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
