<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CategoryRequest;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $category=Category::getAllCategory();
        // return $category;
        if(Auth::user()->role == 'fournisseur') {
            return view('fournisseur.category.index')->with('categories',$category);
        } elseif (Auth::user()->role == 'admin') {
            return view('backend.category.index')->with('categories',$category);
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Auth::user()->role == 'fournisseur') {
            $parent_cats=Category::where('is_parent',1)->orderBy('title','ASC')->get();
        return view('fournisseur.category.form')->with('parent_cats',$parent_cats);
        } elseif (Auth::user()->role == 'admin') {
            $parent_cats=Category::where('is_parent',1)->orderBy('title','ASC')->get();
            return view('backend.category.form',[
                'parent_cats'=> $parent_cats,
                'category' => new Category ()
            ]);
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {

        $data= $request->all();
        $slug=Str::slug($request->title);
        $count=Category::where('slug',$slug)->count();
        if($count>0){
            $slug=$slug.'-'.date('ymdis').'-'.rand(0,999);
        }
        $data['slug']=$slug;
        $data['is_parent']=$request->input('is_parent',0);
        // return $data;
        $status=Category::create($data);
        if($status){
             return redirect()->route('category.index')->with('toast_success','Catégorie enregistrée avec succès !');
        }
        else{
            return back()->with('toast_error','Oups quelques chose se mal passé lors de l\'enregistrement !');
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
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        $parent_cats=Category::where('is_parent',1)->get();
        if(Auth::user()->role == 'fournisseur') {
            return view('fournisseur.category.form')->with('category',$category)->with('parent_cats',$parent_cats);

        } elseif (Auth::user()->role == 'admin') {
            return view('backend.category.form', [
                'category'=> $category,
                'parent_cats'=> $parent_cats
            ]);

        }
    }

    /**
     * Update the specified resource in storage
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request, Category $category)
    {

        $data= $request->all();
        $data['is_parent']=$request->input('is_parent',0);
        // return $data;
        $status=$category->fill($data)->save();
        if($status){
            return redirect()->route('category.index')->with('toast_success','Catégorie modifiée avec succès');
        }
        else{
            return back()->with('toast_error','Oups quelques chose se mal passé lors de la modification !');
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {

        $child_cat_id=Category::where('parent_id',$category)->pluck('id');
        $status=$category->delete();

        if($status){
            if(count($child_cat_id)>0){
                Category::shiftChild($child_cat_id);
            }
            return redirect()->route('category.index')->with('toast_success','Catégorie supprimée avec succès');
        }
        else{
            return back()->with('toast_error','Oups quelques chose se mal passé lors de la suppréssion !');
        }

    }

    public function getChildByParent(Request $request){
        // return $request->all();
        $category=Category::findOrFail($request->id);
        $child_cat=Category::getChildByParentID($request->id);
        // return $child_cat;
        if(count($child_cat)<=0){
            return response()->json(['status'=>false,'msg'=>'','data'=>null]);
        }
        else{
            return response()->json(['status'=>true,'msg'=>'','data'=>$child_cat]);
        }
    }
}
