<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CallController extends Controller
{
    public function create(Request $req)
    {
        $input = $req->all();
        $input['SupportId'] = auth()->user()->Id;
        if (isset($input['NextIntroDate'])) 
        {
            $input['NextIntroDate'] = date('Y-m-d',$input['NextIntroDate']/1000);
            $input['Reminder'] = $input['NextIntroDate'] . ' ' . $input['Time'];
            
        }
        unset($input['_token']);unset($input['Time']);unset($input['NextIntroDate']);
        DB::table('CallTbl')->insert($input);
        return back()->with('success','تماس گرفته شده با موفقیت ثبت شد');
    }
    public function update(Request $req)
    {
        $input = $req->all();
        
        if (isset($input['NextIntroDate'])) 
        {
            $input['NextIntroDate'] = date('Y-m-d',$input['NextIntroDate']/1000);
            $input['Reminder'] = $input['NextIntroDate'] . ' ' . $input['Time'];
            
        }
        unset($input['_token']);unset($input['id']);unset($input['Time']);unset($input['NextIntroDate']);
        DB::table('CallTbl')->where('Id',$req->id)->update($input);
        return redirect(route('user.call.add',[$input['UserId']]))->with('success','تماس گرفته شده با موفقیت ویرایش شد');
    }
}
