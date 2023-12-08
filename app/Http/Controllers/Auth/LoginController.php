<?php

namespace App\Http\Controllers\Auth;

use Socialite;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Requests\UserRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Session;
use Spatie\Newsletter\Facades\Newsletter;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function credentials(Request $request)
    {
        return ['email' => $request->email,'password' => $request->password,'status' => 'active', 'role' => Rule::in(['fournisseur','admin'])];
    }
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function redirect($provider)
    {
        // dd($provider);
        return Socialite::driver($provider)->redirect();
    }

    public function Callback($provider)
    {
        $userSocial =   Socialite::driver($provider)->stateless()->user();
        $users      =   User::where(['email' => $userSocial->getEmail()])->first();
        // dd($users);
        if($users) {
            Auth::login($users);
            return redirect('/')->with('success', 'Vous êtes connecté depuis '.$provider);
        } else {
            $user = User::create([
                'name'          => $userSocial->getName(),
                'email'         => $userSocial->getEmail(),
                'image'         => $userSocial->getAvatar(),
                'provider_id'   => $userSocial->getId(),
                'provider'      => $provider,
            ]);
            return redirect()->route('home');
        }
    }

    // Login
    public function login()
    {
        return view('frontend.pages.login');
    }

    public function loginSubmit(Request $request)
    {
        $data = $request->all();
        if(Auth::attempt(['email' => $data['email'], 'password' => $data['password'],'status' => 'active'])) {
            Session::put('user', $data['email']);
            request()->session()->flash('success', 'Vous étes connecté avec succès !');
            return redirect()->route('home');
        } else {
            request()->session()->flash('error', 'E-mail ou mot de passe incorrect veuillez réessayer plus tard !!');
            return redirect()->back();
        }
    }

    public function logout()
    {
        Session::forget('user');
        Auth::logout();
        request()->session()->flash('success', 'Vous étes déconnecté avec succès ');
        return back();
    }

    public function register()
    {
        return view('frontend.pages.register');
    }


    public function registerSubmit(UserRequest $request)
    {
        // return $request->all();

        $data = $request->validated();
        // dd($data);
        $check = $this->create($data);
        Session::put('user', $data['email']);
        if($check) {
            request()->session()->flash('success', 'Votre est enregistré avec succès ');
            return redirect()->route('home');
        } else {
            request()->session()->flash('error', 'Veuillez réessayer plus tard !');
            return back();
        }
    }

    public function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
            'status' => 'active'
            ]);
    }

    
    // Reset password
    public function showResetForm()
    {
        return view('auth.passwords.old-reset');
    }

    public function subscribe(Request $request)
    {
        if(! Newsletter::isSubscribed($request->email)) {
            Newsletter::subscribePending($request->email);
            if(Newsletter::lastActionSucceeded()) {
                request()->session()->flash('success', 'Abonné ! Merci de consulter vos emails');
                return redirect()->route('home');
            } else {
                Newsletter::getLastError();
                return back()->with('error', 'Quelque chose s\'est mal passé ! Veuillez réessayer');
            }
        } else {
            request()->session()->flash('error', 'Vous êtes déjà inscrit');
            return back();
        }
    }
}
