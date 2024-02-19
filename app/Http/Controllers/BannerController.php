<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\BannerRequest;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $banner=Banner::orderBy('id','DESC')->paginate();
        return view('backend.banner.index')->with('banners',$banner);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.banner.form', [ 'banner' =>  new Banner ()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BannerRequest $request)
    {
        $data=$request->all();
        $slug=Str::slug($request->title);
        $count=Banner::where('slug',$slug)->count();
        if($count>0){
            $slug=$slug.'-'.date('ymdis').'-'.rand(0,999);
        }
        $data['slug']=$slug;
        // return $slug;
        $status=Banner::create($data);
        if($status){
            return redirect()->route('banner.index')->with('toast_success','Bannière enregistré avec succès');
        }
        else{
            return redirect()->back()->with('toast_error','Erreur survenue lors l\'eregistrement ');
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
    public function edit(Banner $banner)
    {
        return view('backend.banner.form', [ 'banner' =>$banner ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(BannerRequest $request, Banner $banner)
    {
        $data=$request->all();

        $status=$banner->fill($data)->save();
        if($status){
            return redirect()->route('banner.index')->with('toast_success','Bannière mofifiée avec succès');
        }
        else{
            return redirect()->back()->with('error','Erreur survenue lors de la modification');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Banner $banner)
    {

        $status=$banner->delete();
        if($status){
            return redirect()->route('banner.index')->with('toast_success','Bannière supprimé avec succès');
        }
        else{
            return redirect()->back()->with('toast_error','Erreur survenue lors de la suppression');
        }

    }
}
