<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Auth;

class RegisterController extends Controller
{
    public function index() {
        $data['title'] = "Register";
        return view('auth.register', $data);
    }

    public function save(Request $request) {
        $request->validate([
            'FirstName' => ['required', 'string', 'max:255'],
            'LastName'  => ['required', 'string', 'max:255'],
            'email'     => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'  => ['required', 'string', 'min:8', 'confirmed'],
        ]);
           
        $user = new User();
        $user->FirstName = $request->FirstName;
        $user->LastName  = $request->LastName;
        $user->email     = $request->email;
        $user->password  = Hash::make($request->password);

        if($user->save()) {
            Auth::login($user);
            $request->session()->put('LoggedUser', Auth::id());
            return redirect("dashboard")->withSuccess('You have signed-in');
        } else {
            return back()->with('fail', 'Something went wrong, try again later');
        }
    }
}
