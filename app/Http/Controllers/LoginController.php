<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Http\Support\Facades\Hash;
class LoginController extends Controller
{
    public function showLoginForm(){
        return view('auth.login');
    }

    public function login(Request $request)
   {
    $credentials = $request->validate([
        'username' => 'required',
        'password' => 'required',
    ]);

    
    $admin = \App\Models\Admin::where('username', $credentials['username'])->first();

    //  using Hash::check)
    if ($admin && \Hash::check($credentials['password'], $admin->password)) {
        
       
        if ($admin->is_admin == 1) {
            
            session(['admin_id' => $admin->id]);
            session(['is_logged_in' => true]);
            
            return redirect()->route('admin.dashboard');
        } else {
            return back()->withErrors(['username' => 'Your account is disabled (State 0).']);
        }
    }

  
    return back()->withErrors(['username' => 'Invalid credentials.']);
}
public function logout(Request $request)
{
    $request->session()->forget(['admin_id', 'is_logged_in']);
    $request->session()->flush();
    
    return redirect()->route('login');
}
}
