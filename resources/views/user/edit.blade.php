@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center" dir="rtl">
        <div class="col-md-12 text-right" >
            <div class="card">
                <div class="card-header text-center">
                    <h5>
                       ویرایش {{$user->Name.' '.$user->Family }}
                    </h5>
                    </div>

                <div class="card-body">
                    <form action="{{route('user.update',[$user->Id])}}" method="post" id="frm">
                    @csrf
                    <div class="form-row col-md-12">
                        <div class="form-group col-md-3">
                            <label for="Perm">نوع کاربر
                                
                            </label>
                            <select class="form-control" name="Perm"  id="Perm" >
                                <option value="0" {{($user->Perm)?'':"selected"}}>دانش آموز</option>
                                <option value="2" {{($user->Perm)?'selected':""}}>اولیا</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="Name">نام
                                <small style="color:red" id="errors_name"></small>
                            </label>
                            <input type="text" class="form-control" id="Name" name="Name" placeholder="نام" value="{{$user->Name}}">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="Family">نام خانوادگی
                                <small style="color:red" id="errors_family"></small>
                            </label>
                            <input type="text" class="form-control" name="Family" placeholder="نام خانوادگی" value="{{$user->Family}}">
                        </div>
                    {{-- </div>
                    <div class="form-row "> --}}
                        <div class="form-group  col-md-3">
                            <label for="Gender">جنسیت</label>
                            <select class="form-control" name="Gender"  id="Gender"  >
                                <option value="0" {{(is_null($user->Gender))?'':"selected"}} disabled>انتخاب نشده</option>
                                <option value="2" {{($user->Gender==2)?'':"selected"}}>دختر</option>
                                <option value="1" {{($user->Gender==1)?'selected':""}}>پسر</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="Phone">تلفن
                                <small style="color:red" id="errors_phone"></small>
                            </label>
                            <input type="number" maxlength="11" class="form-control" id="Phone" name="Phone" placeholder="تلفن" oninput="checkPhoneExists()" value="{{$user->Phone}}">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="Father">شماره تلفن مادر
                                <small style="color:red" id="errors_father"></small>
                            </label>
                            <input type="number" class="form-control" id="Father"  name="Father" placeholder="شماره تلفن مادر" value="{{$user->Father}}" oninput="checkFatherExists()" >
                        </div>
                        
                        <div class="form-group col-md-3">
                            <label for="Birthday">تاریخ تولد</label>
                            <input readonly="readonly" id="Birthday" autocomplete="off" placeholder="وارد نشده"  style="background-color: white;" 
                            type="text" class="form-control"  value="{{($user->BirthDay)?\Morilog\Jalali\CalendarUtils::createCarbonFromFormat('Y-m-d', $user->BirthDay):null}}">
                            <input type="hidden" name="BirthDay" value="{{($user->BirthDay)?\Morilog\Jalali\CalendarUtils::createCarbonFromFormat('Y-m-d', $user->BirthDay):null}}" id="UBirthday">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="Meli">کد ملی
                                <small style="color:red" id="errors_meli"></small>
                            </label>
                            <input id="Meli" type="text" class="form-control" maxlength="10" name="Meli" placeholder="کد ملی" value="{{$user->Meli}}">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="AndroidId">شناسه دستگاه</label>
                            <input id="AndroidId" type="text" class="form-control" name="AndroidId" placeholder="شناسه دستگاه" value="{{$user->AndroidId}}">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="Pass">رمز عبور</label>
                            <input id="Pass" type="text" class="form-control" name="Pass" disabled placeholder="رمز عبور" value="{{$user->Pass}}">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="Active">وضعیت</label>
                            <select class="form-control" id="Active" name="Active">
                                <option value="1" {{($user->Active)?'selected':""}}>فعال</option>
                                <option value="0" {{($user->Active)?'':"selected"}}>غیرفعال</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label>مرحله چالش</label>
                            <input type="number" id="Challenge" name="Challenge" class="form-control" value="{{$user->Challenge}}" />
                        </div>
                        <div class="form-group col-md-3">
                            <label>دغدغه ها</label>
                            @php
                            $Sibling=json_decode($user->Sib)??['Sib'=>0];
                            $worry=json_decode($user->Worry)??[];
                                $wlist=["عدم تمرکز","لجبازی و پرخاشگری","عزت نفس پایین","افزایش انگیزه تحصیلی","روش های صحیح تنبیه و تشویق"
                                ,"اختلالات عادتی(جویدن ناخن)","روش های صحیح تنبیه و تشویق","افسردگی","اضطراب جدایی","اضطراب اجتماعی","وسواس","اختلالات خواب"
                                ,"اختلالات دفع","فوبیا(ترس های فرضی)","اختلالات جنسی","بیش فعالی","اختلالات یادگیری","اوتیسم","سازگاری با طلاق والدین","لکنت زبان","اختلال سوگ"];
                            @endphp
                                        <select class="form-control" multiple name="Worry[]" id="Worry" >
                                            @foreach ($wlist as $index=>$item)
                                                <option {{(in_array(($index),$worry))?"selected":""}} value="{{$index}}">{{$item}}</option>
                                            @endforeach
                                        
                                        </select>
                        </div>
                    </div>
                    <div class="form-raw col-md-12" dir="ltr" style="display:block" >
                            <div class="form-group col-md-12">                           
                            
                                @if($Sibling->Sib!=0)
                                <label> اطلاعات خواهر/برادر
                                    <input type="checkbox" class="checkbox" checked onchange='child2add()' id="child2" name="Sibling"  value="1" >
                                </label>
                                @else
                                <label>ثبت اطلاعات خواهر/برادر
                                    <input type="checkbox" class="checkbox" onchange='child2add()' id="child2" name="Sibling"  value="1" >
                                </label>
                                @endif
                            </div>
                            <div id='childdiv' dir="rtl" class="form-raw col-md-12 border p-3" style="display:{{($Sibling->Sib==0)?'none':'flex'}}">
                                <div class=" col-md-3" >
                                    <label for="SibName">نام
                                        <small style="color:red" id="errors_sibname"></small>
                                    </label>
                                    <input type="text" class="form-control" id="SibName" name="SibName" value="{{$Sibling->Name}}"
                                            placeholder="نام">
                                </div>
                                <div class=" col-md-3" >
                                    <label for="SibMeli">کد ملی
                                        <small style="color:red" id="errors_sibmeli"></small>
                                    </label>
                                    <input type="text" class="form-control" id="SibMeli"  name="SibMeli" value="{{$Sibling->Meli}}"
                                            placeholder="کد ملی">
                                </div>
                                <div class=" col-md-3">
                                    <label for="UserBD2">تاریخ تولد
                                    </label>
                                    <input class="form-control"  id="UserBD2" autocomplete="off" value="{{($user->SibBirthDay)?\Morilog\Jalali\CalendarUtils::createCarbonFromFormat('Y-m-d', $user->SibBirthDay):null}}" readonly style="background-color: white;" placeholder="وارد نشده">
                                </div>
                            </div>
                            <input type="hidden" name="SibBirthDay" value="{{($user->SibBirthDay)?\Morilog\Jalali\CalendarUtils::createCarbonFromFormat('Y-m-d', $user->SibBirthDay):null}}" id="SibBirthday">
                    </div>
                    <div class="form-raw col-md-12">
                        <div class="form-group">
                            <label>توضیح</label>
                            <textarea name="Description" class="form-control">{{$user->Description}}</textarea>
                        </div>
                    </div>
                    </div>
                    <div class="card-footer">
                        <button type="button" id="sbtn" onclick="submitForm()" class="btn btn-success">                       
                            ثبت تغییرات</button>
                        
                    </form>
                        <button onclick="back()" class="btn btn-danger float-left">بازگشت</button>
                </div>
            </div>
        </div>
        <div class="col-md-12 text-right mt-3" >
            <div class="card">
                @include('user.info')
            </div>
        </div>
    </div>
</div>

@endsection
@section('script')
<script type="text/javascript">
var UserBD=UserBD2=null;
recommends= [];
            phone= 0;
            UserId={{ $user->Id}};
            FamilyId= 0;
            
    $(document).ready(function() {
      $("#UserBD2").persianDatepicker({
        format: 'D MMMM YYYY',
        observer: true,
        autoClose: true,
        initialValue: {{($user->SibBirthDay)?'1':'0'}},
        onSelect: function(unix){
            SibBirthday.value = unix;
        }
        });
      $('#Birthday').persianDatepicker({
        format: 'D MMMM YYYY',
        observer: true,
        autoClose: true,
        initialValue: {{($user->BirthDay)?'1':'0'}},
        onSelect: function(unix){
            UBirthday.value = unix;
        }
        });
      $('#MotherBD').persianDatepicker({
        format: 'D MMMM YYYY',
        observer: true,
        autoClose: true,
        initialValue: {{($user->Detail->MotherBirthDay)?'1':'0'}},
        onSelect: function(unix){
            MBD.value = unix;
        }
        });
      $('#FatherBD').persianDatepicker({
        format: 'D MMMM YYYY',
        observer: true,
        autoClose: true,
        initialValue: {{($user->Detail->FatherBirthDay)?'1':'0'}},
        onSelect: function(unix){
            FBD.value = unix;
        }
        });
        @if($user->Detail())
        
        Orientation.value="{{$user->Detail->Orientation}}";
        MotherEducation.value="{{$user->Detail->MotherEducation}}";
        FatherEducation.value="{{$user->Detail->FatherEducation}}";
        City.value="{{$user->Detail->City}}";
        Province.value="{{$user->Detail->Province}}";
        FinancialStatus.value="{{$user->Detail->FinancialStatus}}";
        SpouseRelation.value="{{$user->Detail->SpouseRelation}}";
        ChildCount.value="{{$user->Detail->ChildCount}}";
        Platform.value="{{$user->Detail->Platform}}";
        Fajob.value="{{$jobs[$user->Detail->FatherJob]}}";
        Mojob.value="{{$jobs[$user->Detail->MotherJob]}}";
        @endif
       
    });
    function child2add()
    {
        if(document.getElementById('child2').checked)
        {
            document.getElementById('childdiv').style="display:flex";
        }
        else
            document.getElementById('childdiv').style="display:none";
    }
    function checkPhoneExists()
    {       
        if (Phone.value.length == 11) 
        {
            window.axios.post('{{route("user.edit.checkphone")}}', {OldUserPhone:"{{$user->Phone}}", Perm:"{{$user->Perm}}", NewPhone:Phone.value, UserId:"{{$user->Id}}"})
                .then(function ({data}) {
                    if (data.success) {
                        Swal.fire({
                            icon: 'error',
                            title: 'هشدار!',
                            text: data.message//'شماره کاربر قبلا ثبت شده!'
                        });
                        
                    }
                    else {

                    }
                })
                .catch(error => {
                    console.log(error)
                    Swal.fire({
                            icon: 'error',
                            title: 'خطا!',
                            text: 'خطایی رخ داده مجددا امتحان نمایید'
                    });
                   
                });
        }
    }
    function checkFatherExists()
    {

        if (Perm.value== 0 &&  Father.value.length == 11)
            {
            window.axios.post('{{route("user.edit.checkphone")}}',{OldFatherPhone:"{{$user->Father}}", NewFather:Father.value,Perm:"{{$user->Perm}}"})
                .then(function ({data}) {
                    if (data.success) {
                        Swal.fire({
                            icon: 'error',
                            title: 'هشدار!',
                            text: data.message// 'شماره مادر توسط پشتیبان دیگری ثبت شده'
                        });
                    }
                    else {

                    }
                })
                .catch(error => {
                    console.log(error)
                    Swal.fire({
                    icon: 'error',
                    title: 'خطا!',
                    text: 'خطایی رخ داده مجددا امتحان نمایید'
                    });
                });
        }
    }
     function back()
     {
        window.location.href="{{$backUrl}}";
     }
    function validationForm() 
    {
            document.getElementById('errors_phone').innerHTML ='';
            document.getElementById('errors_father').innerHTML ='';
            document.getElementById('errors_meli').innerHTML ='';
        id="{{auth()->user()->Id}}";
            if( id == 32658)
        {
            return true;
        }
       
        if (!Phone.value)  document.getElementById('errors_phone').innerHTML = 'الزامی است';
        if (!Father.value)  document.getElementById('errors_father').innerHTML = 'الزامی است';
        //if (!Name.value)  document.getElementById('errors_name').innerHTML = 'الزامی است';
        //if (!Family.value)  document.getElementById('errors_family').innerHTML = 'الزامی است';
       
        if (Meli.value && (!$.isNumeric(Meli.value)||Meli.value.length != 10))  document.getElementById('errors_meli').innerHTML = ' را به صورت صحیح وارد کنید';
        if (Meli.value && (!$.isNumeric(SibMeli.value)||SibMeli.value.length != 10))  document.getElementById('errors_sibmeli').innerHTML = ' را به صورت صحیح وارد کنید';

        if (Perm.value==3 && Phone.value.length != 11)  document.getElementById('errors_phone').innerHTML = ' را به صورت صحیح وارد کنید'
        // if (Father.value.length != 11)  document.getElementById('errors_father').innerHTML = ' را به صورت صحیح وارد کنید'
        if (Perm.value==3 && Phone.value.substring(0, 2) != '09')  document.getElementById('errors_phone').innerHTML = ' باید با 09 آغاز شود'
        //  if (Perm.value!=3 && Father.value.substring(0, 2) != '09')  document.getElementById('errors_father').innerHTML = ' باید با 09 آغاز شود'
        //else
            if (Perm.value==3 && Father.value.substring(0, 3) != '031')  document.getElementById('errors_father').innerHTML = ' باید با 031 آغاز شود'
        
        if (Perm.value!=3 && Phone.value.substring(0, 2) == '09' && Phone.value.length != 11)  document.getElementById('errors_phone').innerHTML =' را به صورت صحیح وارد کنید'
        if (Perm.value!=3 && Father.value.substring(0, 2) == '09' && Father.value.length != 11)  document.getElementById('errors_father').innerHTML =' را به صورت صحیح وارد کنید'
        
        
        if(!( document.getElementById('errors_phone').innerHTML ||  document.getElementById('errors_father').innerHTML || document.getElementById('errors_meli').innerHTML ))
        {
            document.getElementById('errors_phone').innerHTML ='';
            document.getElementById('errors_father').innerHTML ='';
            document.getElementById('errors_meli').innerHTML ='';
            return true;
        }
        else
        {
            return false;
        }
    }
    function submitForm() 
    {
        sbtn.disabled=true;
        if(validationForm())
        {var err=ferr=0;
            window.axios.post('{{route("user.edit.checkphone")}}', {OldUserPhone:"{{$user->Phone}}", Perm:"{{$user->Perm}}", NewPhone:Phone.value, UserId:"{{$user->Id}}"})
                .then(function ({data}) {
                    if (data.success) {
                        Swal.fire({
                            icon: 'error',
                            title: 'هشدار!',
                            text: data.message//'شماره کاربر قبلا ثبت شده!'
                        });
                        sbtn.disabled=false;
                    }
                    else {
                        window.axios.post('{{route("user.edit.checkphone")}}',{OldFatherPhone:"{{$user->Father}}", NewFather:Father.value,Perm:"{{$user->Perm}}"})
                            .then(function ({data}) {
                                if (data.success) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'هشدار!',
                                        text: data.message// 'شماره مادر توسط پشتیبان دیگری ثبت شده'
                                    });
                                    sbtn.disabled=false;
                                }
                                else {
                                    frm.submit();

                                }
                            })
                    }
                })
                .catch(error => {
                    console.log(error)
                    Swal.fire({
                            icon: 'error',
                            title: 'خطا!',
                            text: 'خطایی رخ داده مجددا امتحان نمایید'
                    });
                        err=1;
                   
                });
        }
        else
        sbtn.disabled=false;
    }
  </script>
@endsection
