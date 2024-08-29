<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //Register User
    public function register(Request $request){
       //Validate
       $fields=$request->validate([
        'username'=>['required','max:255'],
        'email'=>['required','max:255','email','unique:users'],
        'password'=>['required','min:3','confirmed']
       ]);
       //Register

        $fields['password'] = Hash::make($fields['password']);

       $user=User::create($fields);

       //Login
       Auth::login($user);
       event(new Registered($user));
       //Redirect
       return redirect()->route('dashboard');
    }

    //Verify Email Notice handler
    public function verifyNotice()
    {
        return view('auth.verify-email');
    }

    public function verifyEmail(EmailVerificationRequest $request)
    {
        $request->fulfill();
        return redirect()->route('dashboard');
    }    
     
    //Resending the Verification Email
    public function verifyHandler (Request $request) {
        $request->user()->sendEmailVerificationNotification();
     
        return back()->with('message', 'Verification link sent!');
    }

    //Login User
    public function login(Request $request)
    {
         //Validate
        $fields=$request->validate([            
            'email'=>['required','max:255','email'],
            'password'=>['required']
           ]);
        
           //dd($request);
        //Try to login the user
        if(Auth::attempt($fields, $request->remember))
        {
            return redirect()->intended('dashboard');
        }
        else
        {
            return back()->withErrors([
                'failed'=>'The provided credentials do not match our records.'
            ]);
        }
    }

    //Logout User
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
