<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;

class functionsController extends Controller
{
    public function getData()
    {
        $auth = Session::get('authenticate')['user'];
        $url='http://exam.erfankhoshnazar.com/api/today';
        if($auth['Perm']==3)
        {
           $MyUserPhones= DB::table('SupportUserTbl as SU')
            ->join('UserTbl as U','U.Id','=','SU.UserId')
            ->select('U.Id','U.Phone','SU.SupportId')
            ->where('SupportId',$auth['Id'])
            ->pluck('Phone')
            ->toArray();

            $MyUserFatherPhones= DB::table('SupportUserTbl as SU')
            ->join('UserTbl as U','U.Id','=','SU.UserId')
            ->select('U.Id','U.Father','SU.SupportId')
            ->where('SupportId',$auth['Id'])
            ->pluck('U.Father')
            ->toArray();

            $MyUserPhones=array_unique(array_merge($MyUserPhones,$MyUserFatherPhones));
            $response = Http::post($url,['first_date'=>date('Y-m-d'),'perm'=>3,'phones'=>$MyUserPhones]);
        }
        else
        $response = Http::post($url,['first_date'=>date('Y-m-d')]);
        if($response->ok())
        {
                $examstoday=$response->json();
           
        }
        else 
        $examstoday =0;
        
    if($auth['Perm']==4) 
    {        
        $birthdayCount = DB::table('SupportUserTbl AS S')
                ->join('UserTbl AS U', 'U.Id', '=', 'S.UserId')
                ->join('UserDetailsTbl AS D', 'D.Id', '=', 'U.DetailId')
                ->where(function ($query) {
                    $query->where('U.BirthDay', 'LIKE', '%' . jdate(date('Y-m-d'))->format('m-d'))
                        ->orWhere('D.FatherBirthDay', 'LIKE', '%' . jdate(date('Y-m-d'))->format('m-d'))
                        ->orWhere('U.SibBirthDay', 'LIKE', '%' . jdate(date('Y-m-d'))->format('m-d'))
                        ->orWhere( 'D.MotherBirthDay', 'LIKE', '%' . jdate(date('Y-m-d'))->format('m-d'));
                })
                ->count();
               /* $FBD= DB::table('SupportUserTbl AS S')
                    ->join('UserTbl AS U', 'U.Id', '=', 'S.UserId')
                    ->where('Perm',2)
                    ->where('U.BirthDay', 'LIKE', '%' . jdate(date('Y-m-d'))->format('m-d'))
                    ->get()->count();
                
                $UBD= DB::table('SupportUserTbl AS S')
                    ->join('UserTbl AS U', 'U.Id', '=', 'S.UserId')
                    ->join('UserDetailsTbl AS D', 'D.Id', '=', 'U.DetailId')
                    ->where('Perm',0) 
                    ->where(function ($query) {
                        $query->where('U.BirthDay', 'LIKE', '%' . jdate(date('Y-m-d'))->format('m-d'))
                            ->orWhere('D.FatherBirthDay', 'LIKE', '%' . jdate(date('Y-m-d'))->format('m-d'))
                            ->orWhere('U.SibBirthDay', 'LIKE', '%' . jdate(date('Y-m-d'))->format('m-d'))
                            ->orWhere( 'D.MotherBirthDay', 'LIKE', '%' . jdate(date('Y-m-d'))->format('m-d'));
                    })->get()->count(); */ 
            $data=['allBirthDay'=>$birthdayCount,'FBD'=>$FBD??null,'UBD'=>$UBD??null,'examstoday'=>$examstoday];
    }      
    elseif($auth['Perm']==3)
    {  
            $loginedSupport = $auth['Id'];
            /*$newRows = DB::table('AlarmTbl')->whereIn('SupportId', [$loginedSupport, 0])
                ->where('Active', 1)->count();

            $allTickets = DB::table('UserTbl')->where('Id', $loginedSupport)->first();
            if (isset($allTickets->AlarmCount) && $allTickets->AlarmCount == null) {
                DB::table('UserTbl')->where('Id', $loginedSupport)->where('Perm', 3)->update([
                    'AlarmCount' => 0
                ]);
            }
            elseif (isset($allTickets->AlarmCount) && $allTickets->AlarmCount > 0) {
                $lastCount = $newRows - $allTickets->AlarmCount;
            }
            else {
                $lastCount = $newRows;
            }*/

            
            $birthdayCount = DB::table('SupportUserTbl AS S')
                ->join('UserTbl AS U', 'U.Id', '=', 'S.UserId')
                ->join('UserDetailsTbl AS D', 'D.Id', '=', 'U.DetailId')
                ->where('S.SupportId', $auth['Id'])
                ->where(function ($query) {
                    $query->where('U.BirthDay', 'LIKE', '%' . jdate(date('Y-m-d'))->format('m-d'))
                        ->orWhere('D.FatherBirthDay', 'LIKE', '%' . jdate(date('Y-m-d'))->format('m-d'))
                        ->orWhere('U.SibBirthDay', 'LIKE', '%' . jdate(date('Y-m-d'))->format('m-d'))
                        ->orWhere( 'D.MotherBirthDay', 'LIKE', '%' . jdate(date('Y-m-d'))->format('m-d'));
                })
                ->count();

               /* $FBD= DB::table('SupportUserTbl AS S')
                    ->join('UserTbl AS U', 'U.Id', '=', 'S.UserId')
                    ->where('S.SupportId', $auth['Id'])
                    ->where('Perm',2)
                    ->where('U.BirthDay', 'LIKE', '%' . jdate(date('Y-m-d'))->format('m-d'))->get()->count();
                $UBD= DB::table('SupportUserTbl AS S')
                    ->join('UserTbl AS U', 'U.Id', '=', 'S.UserId')
                     ->join('UserDetailsTbl AS D', 'D.Id', '=', 'U.DetailId')
                     ->where('S.SupportId', $auth['Id'])
                    ->where('Perm',0) 
                    ->where(function ($query) {
                        $query->where('U.BirthDay', 'LIKE', '%' . jdate(date('Y-m-d'))->format('m-d'))
                            ->orWhere('D.FatherBirthDay', 'LIKE', '%' . jdate(date('Y-m-d'))->format('m-d'))
                            ->orWhere('U.SibBirthDay', 'LIKE', '%' . jdate(date('Y-m-d'))->format('m-d'))
                            ->orWhere( 'D.MotherBirthDay', 'LIKE', '%' . jdate(date('Y-m-d'))->format('m-d'));
                    })->get()->count();*/
            
            $unpay=DB::table('PaymentTbl as P')
                    ->join('PaymentTokenTbl as T','T.PaymentId','=','P.Id')
                    ->where('P.Active',1)->where('P.SupportId',$auth['Id'])
                    ->where('T.Active',1)
                    ->where('T.Type',2)
                    ->where('T.Status',0)
                    ->whereDate('T.DeadLine',"=",today())
                    ->orderBy('T.DeadLine')
                    ->orderBy('P.SupportPrice', 'DESC')
                    ->orderBy('T.Price', 'DESC')->get()
                    ->count();
            $data=['unpay'=>$unpay,'allBirthDay'=>$birthdayCount,'FBD'=>$FBD??null,'UBD'=>$UBD??null,'lastCount'=>$lastCount??null,'examstoday'=>$examstoday];
    }
        session(['sidbardata' => $data]);

        if(!session('refreshtime'))
            session(['refreshtime' => ['H'=>date('H'),'m'=>date('i'),'s'=>date('s')]]);
        elseif(session('refreshtime')['H']==date('H') && (date('i') - session('refreshtime')['m']) >2)
          session(['refreshtime' => ['H'=>date('H'),'m'=>date('i'),'s'=>date('s')]]);
        elseif(session('refreshtime')['H']!=date('H'))
          session(['refreshtime' => ['H'=>date('H'),'m'=>date('i'),'s'=>date('s')]]);
        
       return response()->json(['success'=>1,'data'=>$data]);
    }
}
