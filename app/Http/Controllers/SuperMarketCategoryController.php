<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Models\SuperMarketCategory;
use App\Http\Requests\StoreSuperMarketCategoryRequest;


class SuperMarketCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $superMarketCategories = SuperMarketCategory::orderBy('id','DESC')->paginate(10);
        return view('backend.supermarketCategory.index',[
            'superMarketCategories' => $superMarketCategories
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.supermarketCategory.form',[
            'superMarketCategory' => new SuperMarketCategory()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSuperMarketCategoryRequest $request)
    {

        $data= $request->all();
        $slug=Str::slug($request->title);

        $count=SuperMarketCategory::where('slug',$slug)->count();
        if($count>0){
            $slug=$slug.'-'.date('ymdis').'-'.rand(0,999);
        }

        $data['slug']=$slug;

        $status=SuperMarketCategory::create($data);

        if ($status) {
            return redirect()->route('superMarketCategory.index')->with('toast_success','Nouvelle catégorie de supermarché enregistré avec succès');
        } else {
            return redirect()->back()->with( 'toast_error', 'Quelques chose se mal passé lors de l\'enregistre de la catégorie  veuillez réessayer plus tard ');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(SuperMarketCategory $superMarkertCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SuperMarketCategory $superMarketCategory)
    {
     
        return view('backend.supermarketCategory.form',[
            'superMarketCategory' => $superMarketCategory
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreSuperMarketCategoryRequest $request, SuperMarketCategory $superMarketCategory)
    {
        $data= $request->all();
        $slug=Str::slug($request->title);

        $data['slug']=$slug;

        $status = $superMarketCategory->fill($data)->save();

        if ($status) {
            return redirect()->route('superMarketCategory.index')->with('toast_success','Catégorie de supermarché modifié avec succès');
        } else {
            return redirect()->back()->with( 'toast_error', 'Quelques chose se mal passé lors de la modification de la catégorie veuillez réessayer plus tard ');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SuperMarketCategory $superMarketCategory)
    {
        $status = $superMarketCategory->delete();

        if($status) {
           return redirect()->route('superMarketCategory.index')->with('toast_success','Catégorie de supermarché supprimée avec succès');
        } else {
            redirect()->route('superMarketCategory.index')->with('toast_error','Erreur survenue lors de la suppression de cette catégorie !!!');
        }
       
    }
}
