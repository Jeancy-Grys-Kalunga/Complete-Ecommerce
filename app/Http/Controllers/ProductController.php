<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supermarket;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::getAllProduct();
        // return $products;
        if(Auth::user()->role == 'fournisseur') {
            return view('fournisseur.product.index')->with('products', $products);

        } elseif(Auth::user()->role == 'admin') {
            return view('backend.product.index')->with('products', $products);
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $brand = Brand::get();
        $category = Category::where('is_parent', 1)->get();
        $supermakets = Supermarket::all();
        // return $category;
        if(Auth::user()->role == 'fournisseur') {
            return view('fournisseur.product.create')->with('categories', $category)->with('brands', $brand)->with('supermakets', $supermakets);

        } elseif(Auth::user()->role == 'admin') {
            return view('backend.product.create')->with('categories', $category)->with('brands', $brand)->with('supermakets', $supermakets);
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request->all();
        $this->validate($request, [
            'title' => 'string|required',
            'summary' => 'string|required',
            'description' => 'string|nullable',
            'photo' => 'string|required',
            'stock' => "required|numeric",
            'supermarket_id' => 'required|exists:supermarkets,id',
            'cat_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'child_cat_id' => 'nullable|exists:categories,id',
            'user_id' => 'exists:users,id',
            'is_featured' => 'sometimes|in:1',
            'status' => 'required|in:active,inactive',
            'condition' => 'required|in:default,new,hot',
            'price' => 'required|numeric',
            'discount' => 'nullable|numeric'
        ]);

        $data = $request->all();
        $slug = Str::slug($request->title);
        $count = Product::where('slug', $slug)->count();
        if($count > 0) {
            $slug = $slug.'-'.date('ymdis').'-'.rand(0, 999);
        }
        $data['slug'] = $slug;
        $data['is_featured'] = $request->input('is_featured', 0);

        // return $size;
        // return $data;
        $status=Product::create($data);
        if($status){
            request()->session()->flash('success','Produit ajouté avec succès !!');
        }
        else{
            request()->session()->flash('error','Veuillez patienter réessayer plus tard');
        }
        return redirect()->route('product.index');
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
    public function edit($id)
    {
        $brand = Brand::get();
        $product = Product::findOrFail($id);
        $categories = Category::where('is_parent', 1)->get();
        $items = Product::where('id', $id)->get();
        $supermarkets = Supermarket::get();
        // return $items;
        if(Auth::user()->role == 'fournisseur') {
            return view('fournisseur.product.edit', [
                'product' => $product,
                'brands' => $brand,
                'items' => $items,
                'categories' => $categories,
                'supermarket' => $supermarkets
            ]);
        } elseif (Auth::user()->role == 'admin') {
            return view('backend.product.edit', [
                'product' => $product,
                'brands' => $brand,
                'items' => $items,
                'categories' => $categories,
                'supermarkets' => $supermarkets
            ]);
        }

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
        $product = Product::findOrFail($id);
        $this->validate($request, [
            'title' => 'string|required',
            'summary' => 'string|required',
            'description' => 'string|nullable',
            'photo' => 'string|required',
            'stock' => "required|numeric",
            'cat_id' => 'required|exists:categories,id',
            'child_cat_id' => 'nullable|exists:categories,id',
            'supermarket_id' => 'required|exists:supermarkets,id',
            'is_featured' => 'sometimes|in:1',
            'brand_id' => 'nullable|exists:brands,id',
            'status' => 'required|in:active,inactive',
            'condition' => 'required|in:default,new,hot',
            'price' => 'required|numeric',
            'discount' => 'nullable|numeric'
        ]);

        $data = $request->all();
        $data['is_featured'] = $request->input('is_featured', 0);

        // return $data;
        $status = $product->fill($data)->save();
        if($status) {
            request()->session()->flash('success', 'Produit mis à jour avec succès');
        } else {
            request()->session()->flash('error', 'Veuillez patienter réessayer plus tard');
        }
        return redirect()->route('product.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $status = $product->delete();

        if($status) {
            request()->session()->flash('success', 'Produit supprimé avec succès ');
        } else {
            request()->session()->flash('error', 'Erreur survenue lors de la suppression');
        }
        return redirect()->route('product.index');
    }
}
