@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center" dir="rtl">
        <div class="col-md-12 text-right" >
            <div class="card">
                <div class="card-header text-center">
                    <h5>
                       گزارش جدید 
                       <button onclick="back()" class="btn btn-danger float-left">
                        بازگشت به کاربر
                        <i class="fa fa-angle-left"></i>
                        </button>
                    </h5>
                    </div>
                            @php
                                $user=\App\Models\User::find(request('user'));
                                $titlelist=Storage::disk('public_html')->get('Data/ReportTitle.php');
                                    $titlelist=json_decode($titlelist);
                            @endphp
                <div class="card-body">
                    <form action="{{route('user.report.create')}}" method="post" id="frm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="Id" value="{{$user->Id}}">
                    <div class="form-row col-md-12">
                        <div class="form-group col-md-4">
                            <label for="Name">نام
                            </label>
                            <input type="text" class="form-control" disabled  id="Name" name="Name" placeholder="نام" value="{{$user->Name}}">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="Family">نام خانوادگی
                            </label>
                            <input type="text" class="form-control" name="Family" disabled placeholder="نام خانوادگی" value="{{$user->Family}}">
                        </div>
                    {{-- </div>
                    <div class="form-row "> --}}
                       
                        <div class="form-group col-md-4">
                            <label for="Phone">تلفن
                            </label>
                            <input type="number" maxlength="11" class="form-control" id="Phone" name="Phone" placeholder="تلفن" disabled value="{{$user->Phone}}">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="Category">زمینه گزارش
                                <small style="color:red" id="errors_Category"></small>
                            </label>
                            <select class="form-control" id="Category" name="Category">
                                <option  value="1">پنل</option>
                                <option  value="2">اپلیکیشن اندروید</option>
                                <option  value="3">PWA(پی دبِلیو اِی)</option>
                                <option  value="4">دیگر</option>
                            </select> 
                        </div>
                        <div class="form-group col-md-4">
                            <label for="Title">عنوان
                                <small style="color:red" id="errors_Title"></small>
                            </label>
                            <input type="text" list="titles" autocomplete="off" class="form-control" id="Title"   name="Title"  placeholder="عنوان گزارش خود را وارد کنید">
                            <datalist class="form-controlt" id="titles">
                                @foreach ($titlelist as $item)                                    
                                <option value="{{$item}}"></option>
                                @endforeach 
                            </datalist>
                        </div>
                        <div class="form-group col-md-4">
                            <label>شواهد</label>
                            <input type="file" multiple class="form-control" name="File[]" id="File">
                        </div> 
                        <div class="form-group col">
                            <label>توضیح
                                <small style="color:red" id="errors_Description"></small>
                            </label>
                            <textarea name="Description"  id="Description" class="form-control">{{old('Description')}}</textarea>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="button" id="sbtn"  onclick="submitForm()" class="btn btn-success">                       
                            ارسال</button>
                        
                    </form>
                        
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('script')
<script type="text/javascript">

     function back()
     {
        window.location.href="{{route('user.list',['perm'=>$user->Perm]).'?&mobile='.$user->Phone}}";
     }
    function validationForm() 
    {
        document.getElementById('errors_Title').innerHTML ='';
            document.getElementById('errors_Category').innerHTML ='';
            document.getElementById('errors_Description').innerHTML ='';
               
        if (!Title.value)  document.getElementById('errors_Title').innerHTML = 'الزامی است';
        if (!Category.value)  document.getElementById('errors_Category').innerHTML = 'الزامی است';
        if (!Description.value)  document.getElementById('errors_Description').innerHTML = 'الزامی است';
        
        if(!( document.getElementById('errors_Description').innerHTML ||  document.getElementById('errors_Category').innerHTML || document.getElementById('errors_Title').innerHTML ))
        {
            document.getElementById('errors_Title').innerHTML ='';
            document.getElementById('errors_Category').innerHTML ='';
            document.getElementById('errors_Description').innerHTML ='';
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
        {
           frm.submit();
        }
        else
        sbtn.disabled=false;
    }
  </script>
@endsection
