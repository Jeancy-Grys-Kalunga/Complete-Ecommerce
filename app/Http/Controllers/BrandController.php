<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\BrandRequest;
use Illuminate\Support\Facades\Auth;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $brand=Brand::orderBy('id','DESC')->paginate(10);
        if(Auth::user()->role=='fournisseur'){
            return view('fournisseur.brand.index')->with('brands',$brand);
        }elseif(Auth::user()->role=='admin'){
            return view('backend.brand.index')->with('brands',$brand);
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Auth::user()->role=='fournisseur'){
            return view('fournisseur.brand.form', [
                'brand' => new Brand ()
            ]);
        }elseif(Auth::user()->role=='admin'){
            return view('backend.brand.form', [
                 'brand' => new Brand ()
            ]);
        }

    }

    /**
     * Store a newly created resource in storage.
     * @return \Illuminate\Http\Response
     */
    public function store(BrandRequest $request)
    {
    
        $data=$request->all();
        $slug=Str::slug($request->title);
        $count=Brand::where('slug',$slug)->count();
        if($count>0){
            $slug=$slug.'-'.date('ymdis').'-'.rand(0,999);
        }
        $data['slug']=$slug;
        // return $data;
        $status=Brand::create($data);
        if($status){
            if(Auth::user()->role=='fournisseur'){
                return redirect()->route('brand.index')->with('toast_success','Marque enregistrée avec succès');

            }elseif(Auth::user()->role=='admin'){
                return redirect()->route('brand.index')->with('toast_success','Marque enregistrée avec succès');
            }
        }
        else{
            if(Auth::user()->role=='fournisseur'){
                return redirect()->back()->with('toast_error','Erreur veuillez réessayer plus tard');

            }elseif(Auth::user()->role=='admin'){
                return redirect()->back()->with('toast_error','Erreur veuillez réessayer plus tard');
            }
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Brand $brand)
    {
       
        if(!$brand){
            if(Auth::user()->role=='fournisseur'){
                return view('fournisseur.brand.form', [
                    'brand' => $brand
                ]);
            }elseif(Auth::user()->role=='admin'){
                return view('backend.brand.form', [
                     'brand' => $brand
                ]);
            }
        }else{
            return redirect()->back()->with('error','Marque non trouvée ');
        }

    }


    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(BrandRequest $request,Brand $brand)
    {
      
        $data=$request->all();

        $status=$brand->fill($data)->save();
        if($status){
            if(Auth::user()->role=='fournisseur'){
                return redirect()->route('brand.index')->with('toast_success','Marque enregistrée avec succès');

            }elseif(Auth::user()->role=='admin'){
                return redirect()->route('brand.index')->with('toast_success','Marque enregistrée avec succès');
            }
           
        }
        else{
            if(Auth::user()->role=='fournisseur'){
                return redirect()->back()->with('toast_error','Erreur lors de la modification veuillez réessayer plus tard');

            }elseif(Auth::user()->role=='admin'){
                return redirect()->back()->with('toast_error','Erreur survenue lors de la modification veuillez réessayer plus tard');
            }
            
        }
       
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Brand $brand)
    {
      
        if($brand){
            $status=$brand->delete();
            if($status){
                return redirect()->route('brand.index')->with('toast_success','Marque supprimée avec succès');
            }
            else{
                return redirect()->back()->with('toast_error','Erreur lors de la suppression veuillez réessayer plus tard');
            }
            return redirect()->route('brand.index');
        }
        else{
            return redirect()->back()->with('error','Aucune marque trouvée');
            
        }
    }
}
