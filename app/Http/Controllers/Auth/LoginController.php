<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth;
use Session;

class LoginController extends Controller
{
    public function index() {
        if (Auth::check()) {
            if(Auth::user()->Status==1){
                return redirect()->route('dashboard');
            }
            
        }

        $data['title'] = "Login";
        return view('auth.login', $data);
    }

    public function check(Request $request) {
        $request->validate([
            'email'    => 'required',
            'password' => 'required',
        ]);
   
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            if(Auth::user()->Status==1){
                $request->session()->put('LoggedUser', Auth::id());

                $fullname = Auth::user()->FirstName . ' ' . Auth::user()->LastName;
                activity()->log("{$fullname} logged in");
                    
                return redirect()->intended('dashboard')->withSuccess('Signed in');

            } else{
                return back()->with('fail', 'User is deactivated.')->withInput();
            }
           
        } else{
            return back()->with('fail', 'Incorrect email address or password.')->withInput();
        }
  
        
    }

    public function logout() {
        $fullname = Auth::user()->FirstName . ' ' . Auth::user()->LastName;
        activity()->log("{$fullname} logged out");

        Session::flush();
        Auth::logout();
  
        return redirect('/');
    }
}
