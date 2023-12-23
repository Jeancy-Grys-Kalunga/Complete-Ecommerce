<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Settings;
use Illuminate\Http\Request;
use App\Rules\MatchOldPassword;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;
use Spatie\Activitylog\Models\Activity;

class AdminController extends Controller
{
    public function index(){
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
    
     return view('backend.index')->with('users', json_encode($array));


    }

    public function profile(){
        $profile=Auth::user();
        // return $profile;
        if(Auth::user()->role=='fournisseur'){
            return view('fournisseur.users.profile')->with('profile',$profile);
        }elseif(Auth::user()->role=='admin'){
            return view('backend.users.profile')->with('profile',$profile);
        }
        
    }

    public function profileUpdate(Request $request,$id){
        // return $request->all();
        
        $user=User::findOrFail($id);
        $data=$request->all();
        $status=$user->fill($data)->save();
        if($status){
            request()->session()->flash('success','Profile modifié avec succès ');
        }
        else{
            request()->session()->flash('error','Veuillez réessayer plus tard!');
        }
        return redirect()->back();
    }

    public function settings(){
        $data=Settings::first();
        if(Auth::user()->role=='fournisseur'){
            return view('fournisseur.setting')->with('data',$data);
        }elseif(Auth::user()->role=='admin'){
            return view('backend.setting')->with('data',$data);
        }
       
    }

    public function settingsUpdate(Request $request){
        // return $request->all();
        $this->validate($request,[
            'short_des'=>'required|string',
            'description'=>'required|string',
            'photo'=>'required',
            'logo'=>'required',
            'address'=>'required|string',
            'email'=>'required|email',
            'phone'=>'required|string',
        ]);
        $data=$request->all();
        // return $data;
        $settings=Settings::first();
        // return $settings;
        $status=$settings->fill($data)->save();
        if($status){
            request()->session()->flash('success','Paramètres mis à jour avec succès');
        }
        else{
            request()->session()->flash('error','Quelques chose ce mal passé');
        }
        if(Auth::user()->role=='fournisseur'){
            return redirect()->route('fournisseur');
        }elseif(Auth::user()->role=='admin'){
            return redirect()->route('admin');
        }
        
    }

    public function changePassword(){
        if(Auth::user()->role=='fournisseur'){
            return view('fournisseur.layouts.changePassword');
        }elseif(Auth::user()->role=='admin'){
            return view('backend.layouts.changePassword');
        }
        
    }
    public function changPasswordStore(Request $request)
    {
        $request->validate([
            'current_password' => ['required', new MatchOldPassword],
            'new_password' => ['required'],
            'new_confirm_password' => ['same:new_password'],
        ]);

        User::find(Auth::user()->id)->update(['password'=> Hash::make($request->new_password)]);

        return redirect()->route('admin')->with('success','Mot de passe modifié avec succès');
    }

    // Pie chart
    public function userPieChart(Request $request){
        // dd($request->all());
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
     return view('backend.index')->with('course', json_encode($array));
    }

    // public function activity(){
    //     return Activity::all();
    //     $activity= Activity::all();
    //     return view('backend.layouts.activity')->with('activities',$activity);
    // }

    public function storageLink(){
        // check if the storage folder already linked;
        if(File::exists(public_path('storage'))){
            // removed the existing symbolic link
            File::delete(public_path('storage'));

            //Regenerate the storage link folder
            try{
                Artisan::call('storage:link');
                request()->session()->flash('success', 'Successfully storage linked.');
                return redirect()->back();
            }
            catch(\Exception $exception){
                request()->session()->flash('error', $exception->getMessage());
                return redirect()->back();
            }
        }
        else{
            try{
                Artisan::call('storage:link');
                request()->session()->flash('success', 'Successfully storage linked.');
                return redirect()->back();
            }
            catch(\Exception $exception){
                request()->session()->flash('error', $exception->getMessage());
                return redirect()->back();
            }
        }
    }
}
