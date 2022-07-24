<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PhpParser\Node\Stmt\TryCatch;
use UxWeb\SweetAlert\SweetAlert;

class UserController extends Controller
{
    public function list(Request $req,$perm)
    {       
       //$users=User::find(auth()->user()->Id)->MyUsers()->pluck("UserId");
        #search
        $input=$req->all();
        {
            $where=[];
                if(isset($input['status'])){
                    array_push($where,['Status','=',$input['status']]);
                }
        
                if(isset($input['active'])){
                    array_push($where,['Active','=',$input['active']]);
                }
        
                if(isset($input['name'])){
                    array_push($where,['Name','LIKE','%'.$input['name'].'%']);
                }
        
                if(isset($input['family'])){
                    array_push($where,['Family','LIKE','%'.$input['family'].'%']);
                }
        
                if(isset($input['fatherName'])){
                    array_push($where,['FatherName','LIKE','%'.$input['fatherName'].'%']);
                }
        
                if(isset($input['father'])){
                    array_push($where,['Father','LIKE','%'.$input['father'].'%']);
                }
        
        
                if(isset($input['meli'])){
                    array_push($where,['Meli','=',$input['meli']]);
                }
        
                if(isset($input['fromTo'])){
                    array_push($where,['UserFrom','LIKE','%'.$input['fromTo'].'%']);
                }
        
                if(isset($input['mobile'])){
                    array_push($where,['Phone','=',$input['mobile']]);
                }
        
                if(isset($input['date'])){
                    $selectDate = date('Y-m-d',$input['date']/1000);
                    array_push($where,['Date','>',$selectDate.' 00:00:00']);
                }
        
                if(isset($input['tDate'])){
                    $selectDate = date('Y-m-d',$input['tDate']/1000);
                    array_push($where,['Date','<',$selectDate.' 23:59:59']);
                }   
        }
        if(isset($input['paginate'])){
            $paginate = $input['paginate'];
        }
        else{
            $paginate = 10;
        }
               
        $users= User::join('SupportUserTbl as S','UserId','=','UserTbl.Id')
        ->where('SupportId',auth()->user()->Id)
        ->where('Perm',$perm)
        //->whereIn('Id',$users)
        ->where($where)
        ->select(['UserTbl.*','S.Type'])
        //->orderByDesc('Active')
        ->orderByDesc('UserTbl.Id')
        ->paginate($paginate)->appends(request()->except('page'));

        $filter=count($where);
        return view('user.list',compact('users','filter'));
    }
    public function delete(User $user)
    {       
       DB::table('UserTbl')->where('Id',$user->Id)->update(['Active'=>0,'Cancel'=>(!$user->Cancel)]);
        return redirect(route('user.list',['perm'=>$user->Perm]));
    }
    public function edit(User $user)
    {       
        $backUrl=route('user.list',['perm'=>$user->Perm]).'?mobile='.$user->Phone;
        $jobs = Storage::disk('public_html')->get('Data/jobs.php');
        $jobs=json_decode($jobs);
        return view('user.edit',compact('user','backUrl','jobs'));
    }
    public function create(Request $req)
    {    
        $input=$req->all();
        unset($input['_token']);
        //unset($input['Challenge']);
        $input['Date'] = date('Y-m-d H:i:s');
       
           try {
                $input['BirthDay']= isset($input['BirthDay'])?jdate(date('Y-m-d',$input['BirthDay']/1000))->format('Y-m-d'):null;
            } catch (\Throwable $th) {
               $input['BirthDay']=null;
            } 
        if(isset($input['Sibling']))
        {
            try {
                $input['SibBirthDay']=jdate(date('Y-m-d',$input['SibBirthDay']/1000))->format('Y-m-d') ;
            } catch (\Throwable $th) {
               $input['SibBirthDay']=null;
                
            }
            $input['Sib']=json_encode(['Sib'=>$input['Sibling'],'Name'=>$input['SibName'],'Meli'=>$input['SibMeli']]);
            
            unset($input['Sibling']);unset($input['SibName']);unset($input['SibMeli']);
            
        }
        else
        {
            $input['Sib']=json_encode(['Sib'=>0,'Name'=>'','Meli'=>'']);$input['SibBirthDay']=null;
            unset($input['Sibling']);unset($input['SibName']);unset($input['SibMeli']);
        }
        
        $input['Worry']=json_encode($input['Worry']??[]);
        
        $user=DB::table('UserTbl')->insertGetId($input);
        $detail=DB::table('UserDetailsTbl')->insertGetId(['UserId' => $user]);
        DB::table('SupportUserTbl')->insertGetId(['UserId'=>$user,'SupportId'=>auth()->user()->Id,'AppId'=>1]);
        DB::table('UserTbl')->where('Id', $user)->update(['DetailId' => $detail ]);
        
        
        return redirect(route('user.edit',$user))->with('success', 'کاربر با موفقیت ثبت شد');
    }

    public function update_detail(Request $req,User $user)
    {    
        $input=$req->all();
        unset($input['_token']);
        //unset($input['Challenge']);
        
        
           try {
                $input['MotherBirthDay']= jdate(date('Y-m-d',$input['MBD']/1000))->format('Y-m-d');
            } catch (\Throwable $th) {
                unset($input['MotherBirthDay']);               
            }  
           try {
                $input['FatherBirthDay']= jdate(date('Y-m-d',$input['FBD']/1000))->format('Y-m-d');
            } catch (\Throwable $th) {
                unset($input['FatherBirthDay']);               
            }   
        unset($input['FBD']);unset($input['MBD']);

        $jobs = Storage::disk('public_html')->get('Data/jobs.php');
        $jobs=json_decode($jobs);$f=0;
        if(in_array($input['Fajob'],$jobs))
        $input['FatherJob']=array_search($input['Fajob'],$jobs);
        else
        {
            $jobs[count($jobs)]=$input['Fajob'];$f=1;
             $input['FatherJob']=array_search($input['Fajob'],$jobs);
        }
        

        if(in_array($input['Mojob'],$jobs))
        $input['MotherJob']=array_search($input['Mojob'],$jobs);
        else
        {
            $jobs[count($jobs)]=$input['Mojob'];$f=1;
        $input['MotherJob']=array_search($input['Mojob'],$jobs);
        }

        if($f)
        {
            Storage::disk('public_html')->put('Data/jobs.php',json_encode($jobs));
        }
        unset($input['Mojob']); unset($input['Fajob']);
       $input['Updated_at'] = date('Y-m-d H:i:s');
       
            if (!$user->Detail()) {
                
                $detailId = DB::table('UserDetailsTbl')->insertGetId($input);
                DB::table('UserTbl')->where('Id', $user->Id)->update(['DetailId' => $detailId]);
                $message='اطلاعات کاربر با موفقیت ثبت شد';
            }
            else {
                DB::table('UserDetailsTbl')->where('Id', $user->DetailId)->update($input);

                $message='اطلاعات کاربر با موفقیت ویرایش شد';
            }
        
        return back()->with('success', $message);
    }
    public function update(Request $req,$user)
    {    
        $input=$req->all();
        unset($input['_token']);
        //unset($input['Challenge']);
        
        if(isset($input['BirthDay']))
        {
           try {
                $input['BirthDay']= jdate(date('Y-m-d',$input['BirthDay']/1000))->format('Y-m-d');
            } catch (\Throwable $th) {
                unset($input['BirthDay']);
               
            } 
            
        }
        if(isset($input['Sibling']))
        {
            try {
                $input['SibBirthDay']=jdate(date('Y-m-d',$input['SibBirthDay']/1000))->format('Y-m-d');
            } catch (\Throwable $th) {
                unset($input['SibBirthDay']);
            }
            $input['Sib']=json_encode(['Sib'=>$input['Sibling'],'Name'=>$input['SibName'],'Meli'=>$input['SibMeli']]);
            
            unset($input['Sibling']);unset($input['SibName']);unset($input['SibMeli']);
            
        }
        else
        {
            $input['Sib']=json_encode(['Sib'=>0,'Name'=>'','Meli'=>'']);$input['SibBirthDay']=null;
            unset($input['Sibling']);unset($input['SibName']);unset($input['SibMeli']);
        }
        
        $input['Worry']=json_encode($input['Worry']??[]);

        DB::table('UserTbl')->where('Id',$user)->update($input);
        //SweetAlert::success('کاربر با موفقیت بروزرسانی شد');
        //$backUrl=route('user.list',['perm'=>$user->Perm]).'?mobile='.$user->Phone;
        //return view('user.edit',compact('user','backUrl'));
        return back()->with('success', 'کاربر با موفقیت بروزرسانی شد');
    }
    public function checkphone(request $req)
    {
        if(isset($req->OldFatherPhone))
        {
        //FATHER PHONE CHECK
            $response=$this->fatherPhone($req->all());
        }
        if(isset($req->OldUserPhone))
        {
        //USER PHONE CHECK
            $response=$this->userPhone($req);
        }
        return response()->json($response, 200);
    }
    public function userPhone($input) {

        if ($input['OldUserPhone'] == $input['NewPhone']) {
            $response = [
                'success' => false,
                'data' => '',
                'message' => 'شماره خود کاربر'
            ];
           return $response;
        }

        if (auth()->user()->Perm == 4) {
            $response = [
                'success' => false,
                'data' => '',
                'message' => 'سلام ادمین'
            ];
           return $response;
        }

        if ($input['Perm'] == 0)
        {
            $user = User::where('Perm', $input['Perm'])
                ->where('Phone', $input['NewPhone'])
                ->where('Id', '!=', $input['UserId'])
                ->first();

            if(isset($user))
            {
                /*$support = DB::table('SupportUserTbl')
                    ->where('UserId',$user->Id)
                    ->where('SupportId','<>',18)
                    ->first();*/
                  
                    $support=$user->MySupports()->where('SupportId','<>',18)->get();
                if($support->count())
                {
                    if( $support->where('SupportId',auth()->user()->Id)->count()==0 )
                    {
                        DB::table('AlarmTbl')->insert([
                            'Date' => date('Y-m-d H:i:s'),
                            'Description' => 'دانش آموز ' . $user->Name . ' ' . $user->Family . ' درخواست خدمات دانش آموز را دارد',
                            'SupportId' => $support->first()->SupportId,
                            'Active' => 1,
                            'SenderId' =>auth()->user()->Id
                        ]);
                    

                        $support =$support->first()->Support;
                        
                        $response = [
                            'success' => true,
                            'data' => '',
                            'message' => 'دانش آموز: '.$user->Name.' '.$user->Family.' - '.
                                'شماره دانش آموز: '.$user->Phone.' - '.
                                'پشتیبان: '.$support->Name.' '.$support->Family
                        ];
                       return $response;
                    }
                    else
                    {
                        $response = [
                        'success' => false,
                        'data' => '',
                        'message' => 'پشتیبانش خودتی سالار'
                        ];
                       return $response;
                    }
                }
                else{
                    $response = [
                        'success' => true,
                        'data' => '',
                        'message' => 'دانش آموز: '.$user->Name.' '.$user->Family.' - '.
                            'شماره دانش آموز: '.$user->Phone.' - '.
                            'بدون پشتیبان'
                        ];
                       return $response;
                }
            }
            else{
                $response = [
                    'success' => false,
                    'data' => '',
                    'message' => 'اوکیه کاربری یافت نشد'
                ];
               return $response;
            }
        }
        else 
        {
            $child =User::where('Perm', 0)
                ->where('Father', $input['NewPhone'])
                ->first();
            if(isset($child))
            {
                //$supportChild = DB::table('SupportUserTbl')->where('UserId', $child->Id)->first();
                $supportChild=$child->MySupports()->get();
                if ($supportChild->count()) {
                    
                    if (!$supportChild->where('SupportId',auth()->user()->Id)->exists()) 
                    {
                        DB::table('AlarmTbl')->insert([
                            'Date' => date('Y-m-d H:i:s'),
                            'Description' => 'مادر ' . $child->Name . ' ' . $child->Family . ' درخواست خدمات والدین را دارد',
                            'SupportId' => $supportChild->first()->SupportId,
                            'Active' => 1,
                            'SenderId' => auth()->user()->Id
                        ]);
                        $supportChild = $supportChild->first()->Support;
                        $response = [
                            'success' => true,
                            'data' => '',
                            'message' => 'فرزند: ' . $child->Name . ' ' . $child->Family . ' - ' .
                                'شماره فرزند: ' . $child->Phone . ' - ' .
                                'پشتیبان: ' . $supportChild->Name . ' ' . $supportChild->Family
                        ];
                       return $response;
                    }
                     else {
                        $response = [
                            'success' => false,
                            'data' => '',
                            'message' => 'پشتیبانش خودتی سالار'
                        ];
                       return $response;
                    }
                }
                 else {
                    $response = [
                        'success' => true,
                        'data' => '',
                        'message' => 'فرزند: ' . $child->Name . ' ' . $child->Family . ' - ' .
                            'شماره فرزند: ' . $child->Phone . ' - ' .
                            'بدون پشتیبان'
                    ];
                   return $response;
                }
            }
            else {
                $user = User::where('Perm', $input['Perm'])
                ->where('Phone', $input['NewPhone'])
                ->where('Id', '!=', $input['UserId'])
                ->first();
                
                if(isset($user)){
                    $support = $user->MySupports()
                        ->where('SupportId','<>',18)
                        ->get();

                    if($support->count())
                    {
                        if($support->where('SupportId',auth()->user()->Id)->count()==0)
                        {
                            DB::table('AlarmTbl')->insert([
                                'Date' => date('Y-m-d H:i:s'),
                                'Description' => 'کاربر ' . $user->Name . ' ' . $user->Family . ' درخواست خدمات والدین را دارد',
                                'SupportId' => $support->first()->SupportId,
                                'Active' => 1,
                                'SenderId' => auth()->user()->Id
                        ]);
                            $support = $support->first()->Support;
                            $response = [
                                'success' => true,
                                'data' => '',
                                'message' => 'ولی: '.$user->Name.' '.$user->Family.' - '.
                                    'شماره ولی: '.$user->Phone.' - '.
                                    'پشتیبان: '.$support->Name.' '.$support->Family
                            ];
                           return $response;
                        }
                        else
                        {
                            $response = [
                            'success' => false,
                            'data' => '',
                            'message' => ''
                            ];
                           return $response;
                        }                     
                        
                        
                    }
                    else{
                        $response = [
                            'success' => true,
                            'data' => '',
                            'message' => 'ولی: '.$user->Name.' '.$user->Family.' - '.
                                'شماره ولی: '.$user->Phone.' - '.
                                'بدون پشتیبان'
                        ];
                       return $response;
                    }
                }
                else{
                    $response = [
                        'success' => false,
                        'data' => '',
                        'message' => 'اوکیه! کاربری نیس'
                    ];
                   return $response;
                }
            }
        }
    }

    public function fatherPhone($input)
    {
        if ($input['OldFatherPhone'] == $input['NewFather']) {
            $response = [
                'success' => false,
                'data' => '',
                'message' => ''
            ];
           return $response;
        }

        if (auth()->user()->Id == 4) {
            $response = [
                'success' => false,
                'data' => '',
                'message' => ''
            ];
           return $response;
        }

        $user =User::where('Perm', 2)
            ->where('Phone', $input['NewFather'])
            ->first();

        if (isset($user))
         {
            $supportFather = $user->MySupports->get();
            if ($supportFather->count()) 
            {
                if($supportFather->where('SupportId',auth()->user()->Id)->count()==0)
                {
                    DB::table('AlarmTbl')->insert([
                        'Date' => date('Y-m-d H:i:s'),
                        'Description' => 'فرزند ' . $user->Name . ' ' . $user->Family . ' درخواست خدمات دانش آموزی دارد',
                        'SupportId' => $supportFather->first()->SupportId,
                        'Active' => 1,
                        'SenderId' =>auth()->user()->Id
                    ]);
                    $supportFather =$supportFather->first()->Support;
                    $response = [
                        'success' => true,
                        'data' => '',
                        'message' => 'ولی: ' . $user->Name . ' ' . $user->Family . ' - ' .
                            'شماره ولی: ' . $user->Phone . ' - ' .
                            'پشتیبان: ' . $supportFather->Name . ' ' . $supportFather->Family
                    ];
                   return $response;
                } 
                else {
                    $response = [
                        'success' => false,
                        'data' => '',
                        'message' => ''
                    ];
                   return $response;
                }
            }
             else {
                $response = [
                    'success' => true,
                    'data' => '',
                    'message' => 'ولی: ' . $user->Name . ' ' . $user->Family . ' - ' .
                        'شماره ولی: ' . $user->Phone . ' - ' .
                        'بدون پشتیبان'
                ];
               return $response;
            }
        } 
        else {
            $response = [
                'success' => false,
                'data' => '',
                'message' => ''
            ];
           return $response;
        }
    }
    
    public function createFiles()
    {
        if(!Storage::disk('public_html')->exists('Data/jobs.php'))
        {
            $jobs= [
                "انتخاب نشده","کارمند ساده","نظامی","فرهنگی","آزاد","بیکار","خانه دار",
                "پرستار","مشاور و روانشناس","راننده","آشپز","تاسیسات","مهندس","کارگر",
                "مدرس","فروشنده","هنرمند","کشاورز"
            ];
            Storage::disk('public_html')->put('Data/jobs.php',json_encode($jobs));
        } 
         if(!Storage::disk('public_html')->exists('Data/ReportTitle.php'))
        {
            $title= ["صفحه قرمز ورود","عدم ورود به اپلیکیشن","عدم ارسال کد استعدادیابی","عدم وجود کاربر در لیست استعدادیابی"
              , "لود نشدن ویدیو 360 درجه", "باز نشدن درب ها","ارور تماس با پشتیبان هنگام ورود","ارور تماس با پشتیبان کاخ ها",
              "عدم ورود به کاخ والدین","ارور 419 لینک استعدادیابی","مشکل نصب در لپ تاپ"
              , "عدم بارگذاری چالش ها","باز نشدن سطح بعدی","باز نشدن مرحله بعدی","مشکل در عینک واقعیت مجازی","مشکل در نسخه جدید"
            ];
            Storage::disk('public_html')->put('Data/ReportTitle.php',json_encode($title)); 
       }
    }
}
