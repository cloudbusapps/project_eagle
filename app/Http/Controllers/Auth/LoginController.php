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
        if (Auth::attempt($credentials)) {
            $request->session()->put('LoggedUser', Auth::id());

            $fullname = Auth::user()->FirstName . ' ' . Auth::user()->LastName;
            activity()->log("{$fullname} logged in");
            return redirect()->intended('dashboard')->withSuccess('Signed in');
        }
  
        return back()->with('fail', 'Access Denied!')->withInput();
    }

    public function logout() {
        $fullname = Auth::user()->FirstName . ' ' . Auth::user()->LastName;
        activity()->log("{$fullname} logged out");

        Session::flush();
        Auth::logout();
  
        return redirect('/');
    }
}
