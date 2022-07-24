<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\PaymentToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PaymentTokenController extends Controller
{
    
    public function create(Request $req,Payment $payment)
     {
        $input = $req->all();
            
            unset($input['_token']);
        $input['paymentId']=$payment->Id;
        $input['DeadLine'] = date('Y-m-d',$input['DeadLine']/1000);

        if ($req->hasFile('PaymentImage'))
         {
            $file = $req->file('PaymentImage');
            $path=public_path('Payment/Token/Images');
            if(!is_dir($path))
               {
                  Storage::makeDirectory($path);
               } 
               
               $fileName='p'.$payment->Id . '_user.' .$payment->UserId.'_'.time().".{$file->extension()}"; //{$ex[count($ex)-1]}";//$file->getClientOriginalName();
               $file->move($path,$fileName);              
               $input['PaymentImage']='/Payment/Token/Images/'.$fileName;            
        }
        if($input['Type']==1)
        {
            $input['Status']=1; 
            $input['Collection']= $input['DeadLine'];//date('Y-m-d')
            $tokens = $payment->Tokens()->where('Active', 1)->count();
            if($tokens==0)
            {
                DB::table('PaymentTbl')
                ->where('Id', $payment->Id)
                ->update(['Date'=> $input['Collection']]);
            }
            
        }

        $sid=$payment->SupportId;
        $suids=$payment->User->MySupports()->pluck('SupportId')->toArray();

            if(!in_array($sid,$suids) )//payment support is not user's support 
            {
                if(!in_array(auth()->user()->Id,$suids))//i'm not user support
                {
                    DB::table('PaymentTbl')->where('Id',$payment->Id)
                    ->update(['SupportId'=> $suids[0]]);
                }
                else// i'm user support
                {DB::table('PaymentTbl')->where('Id',$payment->Id)
                ->update(['SupportId'=>auth()->user()->Id]);}
            }  
            else//payment support is user's support 
            {
                if(in_array(auth()->user()->Id,$suids))//i'm user support
                {
                    DB::table('PaymentTbl')->where('Id',$payment->Id)
                    ->update(['SupportId'=>auth()->user()->Id]);
                }
            }        
            
        DB::table('PaymentTokenTbl')->insert($input);
        return back()->with('success','تراکنش کاربر با موفقیت ثبت شد');
     }
    public function complete($id, Request $req)
    {
        $date=($req->date)?date('Y-m-d H:i:s',$req->date/1000):date('Y-m-d H:i:s');
        DB::table('PaymentTokenTbl')->where('Id', $id)->update([
            'Status' => 1,
            'Collection' => $date,
            'Update'=>now()
        ]);         
        return back()->with('success','تراکنش با موفقیت تسویه شد');
    }
    
    public function Update(Request $req,PaymentToken $token)
     {
        $input = $req->all();
            unset($input['_token']); 
        //$input['DeadLine'] =(isset($input['DeadLine']))? date('Y-m-d',$input['DeadLine']/1000):$token->DeadLine;

        if ($req->hasFile('PaymentImage'))
         {
            $file = $req->file('PaymentImage');
            $path=public_path('Payment/Token/Images');
            if(!is_dir($path))
               {
                  Storage::makeDirectory($path);
               } 
               
               $fileName='p'.$token->PaymentId . '_user.' .$token->Payment->UserId.'_'.time().".{$file->extension()}"; //{$ex[count($ex)-1]}";//$file->getClientOriginalName();
               $file->move($path,$fileName);              
               $input['PaymentImage']='/Payment/Token/Images/'.$fileName;            
        }
        if ($input['DeadLine'] == $token->DeadLine) 
            $input['DeadLine'] = $token->DeadLine;
        else 
            $input['DeadLine'] = date('Y-m-d', $input['DeadLine']/1000);
        if($input['Type']==1)
        {
            $input['Status']=1; 
            $input['Collection']= $input['DeadLine']; //date('Y-m-d');
            
        }
        DB::table('PaymentTokenTbl')->where('Id', $token->Id)->update($input);
        return redirect(route('payment.tokens',[$token->Payment->UserId,$token->PaymentId]))->with('success','تراکنش کاربر با موفقیت ویرایش شد');
     }
    public function Edit($token, Request $req)
    {
        $edit_token=PaymentToken::find($token);
        $payment=$edit_token->Payment;
        $user=$payment->User;
       $tokens=$payment->Tokens()->where('Active', 1)->get();
      
       $totalPayment = $tokens
       ->where('Status', 1)
       ->sum('Price');
       $allToken =$tokens->sum('Price');    
              
       $product = ['Description' => $payment->Description, 'Id' => $payment->Id,'Place'=>$payment->Place];
       $UserId = $payment->UserId;
       $saveToken = 0;
       if ($allToken >= $payment->SupportPrice) {
           $saveToken = 1;
       }
       if ($totalPayment >= $payment->SupportPrice) {
           DB::table('PaymentTbl')->where('Id', $payment->Id)->update([
               'Status' => 1
           ]);
       }

       $currentDebt = 0;
       foreach ($tokens as $Ptoken) {
           $currentDebt += $Ptoken->Price;
           $Ptoken->LeftOver = $payment->SupportPrice - $currentDebt;
       }
       $backUrl=route('payment.list',[$UserId]);
       $totalDebt = $payment->SupportPrice - $totalPayment;

       return view('user.payment.token.list', compact('payment','backUrl', 'user', 'product','edit_token', 'saveToken', 'totalPayment', 'totalDebt', 'tokens'));
  
    }
    public function delete(PaymentToken $token)
     {
       
        DB::table('PaymentTokenTbl')->where('Id', $token->Id)->update(['Active'=>0]);
        return redirect(route('payment.tokens',[$token->Payment->UserId,$token->PaymentId]));
     }
}
