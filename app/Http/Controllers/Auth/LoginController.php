<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Socialite;
use Str;
use Hash;
use App\Models\User;
use App\Models\Role;

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
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function redirectTo()
    {
        if (auth()->user()->is_admin) {
            return '/admin';
        }

        return '/home';
    }

    public function google() {
        // send the user's request to google
        return Socialite::driver('google')->redirect();
    }

    public function googleRedirect() {
        // get oauth request back from google to authenticate user
        $user = Socialite::driver('google')->user();

        // if the user doesn't exist, add them
        // if they do, get the model
        // either way, authenticate the user into the application and redirect afterwards
        $user = User::firstOrCreate([
            'email' => $user->email
        ], [
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'password' => Hash::make(Str::random(24)),
            'verified' => true
        ]);

        // Make sure they're saved as a User role
        $role = Role::where('id', 2)->get();
        $user->roles()->syncWithoutDetaching($role);

        \Auth::login($user, true);

        if (auth()->user()->is_admin) {
            return redirect('/admin');
        }

        return redirect('/home');
    }

    public function facebook() {
        // send the user's request to facebook
        return Socialite::driver('facebook')->redirect();
    }

    public function facebookRedirect() {
        // get oauth request back from facebook to authenticate user
        $user = Socialite::driver('facebook')->user();

        // if the user doesn't exist, add them
        // if they do, get the model
        // either way, authenticate the user into the application and redirect afterwards
        $user = User::firstOrCreate([
            'email' => $user->email
        ], [
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'password' => Hash::make(Str::random(24)),
            'verified' => true
        ]);

        // Make sure they're saved as a User role
        $role = Role::where('id', 2)->get();
        $user->roles()->syncWithoutDetaching($role);
        
        \Auth::login($user, true);

        if (auth()->user()->is_admin) {
            return redirect('/admin');
        }

        return redirect('/home');
    }

    public function twitter() {
        // send the user's request to twitter
        return Socialite::driver('twitter-oauth-2')->redirect();
    }

    public function twitterRedirect() {
        // get oauth request back from twitter to authenticate user
        $user = Socialite::driver('twitter-oauth-2')->user();

        // if the user doesn't exist, add them
        // if they do, get the model
        // either way, authenticate the user into the application and redirect afterwards
        $user = User::firstOrCreate([
            'email' => $user->email
        ], [
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'password' => Hash::make(Str::random(24)),
            'verified' => true
        ]);

        // Make sure they're saved as a User role
        $role = Role::where('id', 2)->get();
        $user->roles()->syncWithoutDetaching($role);
        
        \Auth::login($user, true);

        if (auth()->user()->is_admin) {
            return redirect('/admin');
        }

        return redirect('/home');
    }

    public function linkedin() {
        // send the user's request to linkedin
        return Socialite::driver('linkedin')->redirect();
    }

    public function linkedinRedirect() {
        // get oauth request back from linkedin to authenticate user
        $user = Socialite::driver('linkedin')->user();

        // if the user doesn't exist, add them
        // if they do, get the model
        // either way, authenticate the user into the application and redirect afterwards
        $user = User::firstOrCreate([
            'email' => $user->email
        ], [
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'password' => Hash::make(Str::random(24)),
            'verified' => true
        ]);

        // Make sure they're saved as a User role
        $role = Role::where('id', 2)->get();
        $user->roles()->syncWithoutDetaching($role);
        
        \Auth::login($user, true);

        if (auth()->user()->is_admin) {
            return redirect('/admin');
        }

        return redirect('/home');
    }

    public function github() {
        // send the user's request to github
        return Socialite::driver('github')->redirect();
    }

    public function githubRedirect() {
        // get oauth request back from linkedin to authenticate user
        $user = Socialite::driver('github')->user();

        // if the user doesn't exist, add them
        // if they do, get the model
        // either way, authenticate the user into the application and redirect afterwards
        $user = User::firstOrCreate([
            'email' => $user->email
        ], [
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'password' => Hash::make(Str::random(24)),
            'verified' => true
        ]);

        // Make sure they're saved as a User role
        $role = Role::where('id', 2)->get();
        $user->roles()->syncWithoutDetaching($role);
        
        \Auth::login($user, true);

        if (auth()->user()->is_admin) {
            return redirect('/admin');
        }

        return redirect('/home');
    }

    public function microsoft() {
        // send the user's request to microsoft
        return Socialite::driver('microsoft')->redirect();
    }

    public function microsoftRedirect() {
        // get oauth request back from microsoft to authenticate user
        $user = Socialite::driver('microsoft')->user();

        // if the user doesn't exist, add them
        // if they do, get the model
        // either way, authenticate the user into the application and redirect afterwards
        $user = User::firstOrCreate([
            'email' => $user->email
        ], [
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'password' => Hash::make(Str::random(24)),
            'verified' => true
        ]);

        // Make sure they're saved as a User role
        $role = Role::where('id', 2)->get();
        $user->roles()->syncWithoutDetaching($role);
        
        \Auth::login($user, true);

        if (auth()->user()->is_admin) {
            return redirect('/admin');
        }

        return redirect('/home');
    }

}
