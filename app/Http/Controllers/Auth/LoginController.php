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
            return redirect()->route('dashboard');
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
        if (Auth::attempt(['email'=> $request->email,'password' => $request->password,'Status'=>1])) {
            $request->session()->put('LoggedUser', Auth::id());

            $fullname = Auth::user()->FirstName . ' ' . Auth::user()->LastName;
            activity()->log("{$fullname} logged in");
                
            return redirect()->intended('dashboard')->withSuccess('Signed in');
        }
  
        return back()->with('fail', 'Incorrect email address, password or deactivated.')->withInput();
    }

    public function logout() {
        $fullname = Auth::user()->FirstName . ' ' . Auth::user()->LastName;
        activity()->log("{$fullname} logged out");

        Session::flush();
        Auth::logout();
  
        return redirect('/');
    }
}
