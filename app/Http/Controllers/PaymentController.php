<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function list(User $user)
    {
       $fiscalHistory=$user->Payments()->where('Active', 1)->orderBy('Id', 'DESC')->get();
      
        $collected =$fiscalHistory->map(function ($history)
        {
            $collected= $history->Tokens()->where('Active', 1)->sum('Price');
            $history->LeftOver = $history->SupportPrice - $collected;
        });
            
        
       $apps = DB::table('ProductTbl as P')
            ->join('AppTbl as A', 'A.Id', '=', 'P.BelongsId')
            ->where('P.Active', 1)
            ->select(['P.*', 'A.Name'])->orderBy('P.BelongsId')->get();
            
        $userTransactions = $user->Tokens()
            ->leftJoin('AppTbl','AppTbl.Id','=','TokenTbl.AppId')
            ->select('TokenTbl.*','AppTbl.Name as AppName')
            ->get();
            $backUrl = route('user.list',[$user->Perm]).'?mobile='.$user->Phone;
            if(request('payment'))
            $edit=Payment::find(request('payment'));
            else
            $edit=null;
        return view('user.payment.list',compact("edit",'fiscalHistory','apps','userTransactions','user','backUrl'));
    }
    public function AppPrice(Request $request) {
        $input = $request->all();
        $old = DB::table('PaymentTbl')->where('UserId', $input['UserId'])->where('AppId', $input['AppId'])
            ->where('Active', 1)->first();
        if ($old) {
            $response = [
                'success' => false,
                'data' => '',

                'message' => 'محصول انتخاب شده قبلا خریداری شده است'
            ];
            return response()->json($response, 200);
        }
        
        $price = DB::table('ProductTbl as P')
            ->join('AppTbl as A', 'A.Id', '=', 'P.BelongsId')
            ->where('P.Id', $input['AppId'])->select('P.Price')->first();
         
        $response = [
            'success' => true,
            'data' => ['Total' => $price->Price],
            'message' => ''
        ];
        return response()->json($response, 200);

    }
    public function create(Request $req,$user)
    {
       
        $input=$req->all();
        unset($input['_token']);
        $input['SupportId'] = auth()->user()->Id;
        $input['UserId'] = $user;
        $input['Date'] = date('Y-m-d H:i:s');

        $message= 'درخواست کاربر در لیست انتظار قرار گرفت';

        if ($input['DefaultPrice'] == $input['SupportPrice']) {
            $input['Confirm'] = 1;
            $rmessage= 'درخواست کاربر با موفقیت ثبت شد';
        }
        DB::table('PaymentTbl')->insert($input);
        return back()->with('success', $message);
    }
    public function show(User $user,Payment $payment)
    {
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
       foreach ($tokens as $token) {
           $currentDebt += $token->Price;
           $token->LeftOver = $payment->SupportPrice - $currentDebt;
       }
       $backUrl=route('payment.list',[$UserId]);
       $totalDebt = $payment->SupportPrice - $totalPayment;

       return view('user.payment.token.list', compact('payment','backUrl', 'user', 'product', 'saveToken', 'totalPayment', 'totalDebt', 'tokens'));
  
    }
    public function checkPrice(Request $request)
     {
        $input = $request->all();

        $total = DB::table('PaymentTbl')->where('Id', $input['PaymentId'])->select('SupportPrice')->first();
        if (isset($input['Id'])) {
            $totalPrice = DB::table('PaymentTokenTbl')
                ->where('PaymentId', $input['PaymentId'])
                ->where('Id', '!=', $input['Id'])
                ->where('Active', 1)
                ->sum('Price');
        }
        else {
            $totalPrice = DB::table('PaymentTokenTbl')
                ->where('PaymentId', $input['PaymentId'])
                ->where('Active', 1)
                ->sum('Price');
        }
        
        if (($input['Price'] + $totalPrice) > $total->SupportPrice) {
            $response = [
                'success' => true,
                'data' => '',
                'message' => 'مبلغ وارد شده از مبلغ فروخته شده بیشتر است'
            ];
            return response()->json($response, 200);
        }
        else {
            $response = [
                'success' => false,
                'data' => '',
                'message' => ''
            ];
            return response()->json($response, 200);
        }
    }
    public function update(Request $req)
    {
       
        $input=$req->all();
        $payment=Payment::find($input['id']);
        unset($input['id']);
        unset($input['_token']);

        $message= 'درخواست کاربر در لیست انتظار قرار گرفت';

        if ($input['DefaultPrice'] == $input['SupportPrice']) {
            $input['Confirm'] = 1;
            $message= 'درخواست کاربر با موفقیت ویرایش شد';
        }
        else
            $input['Confirm'] = 0;
        DB::table('PaymentTbl')->where('Id',$payment->Id)->update($input);
        return redirect(route('payment.list',[$payment->UserId]))->with('success', $message);
    }
    public function Delete(User $user,Payment $payment)
    {
        DB::table('PaymentTbl')->where('Id',$payment->Id)->update(['Active'=>0]);
        $message="دوره های خریداری شده" .$payment->Product->App->Name.' حذف شد';
        return redirect(route('payment.list',[$payment->UserId]));
    }
}
