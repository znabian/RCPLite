@extends('layouts.app')

@section('content')
<div class="container">
    
    @IF(request('perm')==0)
        @include('user.student_filter')
    @else
        @include('user.parent_filter')
    @endif

    <div class="row justify-content-center" dir="rtl">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header text-center">
                    <h5>
                        {{(request('perm')==0)?'لیست دانش آموزان':"لیست اولیا"}}
                        <a href="{{route('user.new',[request('perm')])}}" class="btn btn-success pull-left">
                            <i class="fa fa-plus"></i>
                            افزودن
                        </a>
                        @if($filter)
                        <a href="{{route('user.list',[request('perm')])}}" class="btn btn-danger pull-right">
                            <i class="fa fa-close"></i>
                            لغو فیلتر
                        </a>
                        @endif
                    </h5>
                    </div>

                <div class="card-body">
                    <div class="table-responsive">
                    <table class="table table-striped text-center">
                        <thead>
                            <tr>
                                <th scope="col">ردیف</th>
                                <th scope="col"> کاربر</th>
                                <th scope="col">شماره همراه</th>
                                <th scope="col">شماره والدین</th>
                                <th scope="col">تاریخ ثبت</th>
                                <th scope="col">وضعیت</th>
                                <th scope="col">گزارش</th>
                                <th scope="col">تماس</th>
                                <th scope="col">خرید</th>
                                <th scope="col">مشاهده / ویرایش</th>
                                <th scope="col">حذف</th>
                                <th scope="col">کنسلی</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                           
                                <tr>
                                <td scope="row">
                                    {{$user->Id}}
                                    <br>
                                    <label class="label label-{{($user->Type)?'warning':"success"}}">
                                        {{($user->Type)?'کارشناسی پشتیبانی':"کارشناسی فروش"}}
                                    </label>
                                    
                                </td>
                                <td>{{$user->Name}}<br>{{$user->Family}}</td>
                                <td>{{$user->Phone}}</td>
                                <td>{{$user->Father}}</td>
                                <td>
                                    <label class="label label-primary">{{jdate($user->Date)->format('Y-m-d')}}</label>
                                </td>
                                <td>
                                    <label class="label label-{{($user->Active)?"success":(($user->Cancel)?'default':"warning")}}">
                                    {{($user->Active)?"فعال":(($user->Cancel)?'کنسل شده':"غیرفعال")}}</label>
                                </td>
                                <td>
                                        <button onclick="location.href='{{route('user.report.add',[$user->Id])}}'" class="btn btn-danger">
                                    <i class="fa fa-exclamation-circle"></i>  
                                        ثبت گزارش</button>
                                </td>
                                <td>
                                        <button onclick="location.href='{{route('user.call.add',[$user->Id])}}'" class="btn btn-success">
                                    <i class="fa fa-phone"></i>  
                                        ثبت تماس</button>
                                </td>
                                <td>
                                    <button onclick="location.href='{{route('payment.list',[$user->Id])}}'" class="btn btn-coral">
                                <i class="fa fa-shopping-cart"></i>  
                                    تاریخچه خرید</button>
                                </td>
                                <td>
                                    <button onclick="location.href='{{route('user.edit',[$user->Id])}}'" class="btn btn-info">
                                  <i class="fa fa-edit"></i>  
                                    ویرایش</button>
                                </td>
                                <td>
                                    <button onclick="deluser('{{$user->Id}}',1);" class="btn btn-danger">
                                  <i class="fa fa-trash-o"></i>  
                                    حذف</button>
                                </td>
                                <td>
                                    @if(!$user->Cancel)
                                        <button onclick="deluser('{{$user->Id}}',0);" class="btn btn-primary">
                                    <i class="fa fa-gavel"></i>  
                                        کنسل</button>
                                    @else
                                        <button onclick="deluser('{{$user->Id}}',2);" class="btn btn-primary">
                                    <i class="fa fa-gavel"></i>  
                                        لغو</button>
                                    @endif
                                </td>

                            </tr>
                           
                            
                            @endforeach

                        </tbody>
                    </table>

                    </div>
                    {{$users->links()}}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('script')
<script>
     $(document).ready(function() {
      $("#trSearchDate").persianDatepicker({
        format: 'D MMMM YYYY',
        observer: true,
        autoClose: true,
        initialValue: false,
        onSelect: function(unix){
            date.value  = unix;
        }
        });
      $('#toTrSearchDate').persianDatepicker({
        format: 'D MMMM YYYY',
        observer: true,
        autoClose: true,
        initialValue: false,
        onSelect: function(unix){
            tDate.value = unix;
        }
        });
    });
function collapse()
{
    if(filter.style.display)
    {
        filter.style.display=''; 
        minbtn.classList.remove("fa-plus");
        minbtn.classList.add("fa-minus");
    }
    
    else
    {
       filter.style.display="none";
        minbtn.classList.add("fa-plus");
        minbtn.classList.remove("fa-minus");
    }
   
}
function submitsearch()
{
    url="{{route('user.list',[request('perm')])}}"+'?';
    if(active.value)
    url+='&active='+active.value;
    if(name.value)
    url+='&name='+name.value;
    if(family.value)
    url+='&family='+family.value;
    if(mobile.value)
    url+='&mobile='+mobile.value;
    if(date.value)
    url+='&date='+date.value;
    if(tDate.value)
    url+='&tDate='+tDate.value;
    if(paginate.value)
    url+='&paginate='+paginate.value;

    @if(request('perm')==0)
    if(fatherName.value)
    url+='&fatherName='+fatherName.value;
    if(father.value)
    url+='&father='+father.value;
    @endif

    document.location.href=url;
}
function deluser(id,del)
{
    if(del==1)
   { title="حذف";msg='از '+title+' کاربر اطمینان دارید؟';}
    else if(del==2)
    {title="از لیست خارج";msg='کاربر از لیست کنسلی خارج شود؟';}
    else
    {title="کنسل";msg='کاربر به لیست کنسلی اضافه شود؟';}
    const swalWithBootstrapButtons = Swal.mixin({
  customClass: {
    confirmButton: 'btn btn-success',
    cancelButton: 'btn btn-danger'
  },
  buttonsStyling: false
})

swalWithBootstrapButtons.fire({
  title: msg,
  text: "",
  icon: 'warning',
  showCancelButton: true,
  confirmButtonText:  'بله, '+title+' شود',
  cancelButtonText: 'لغو عملیات',
  reverseButtons: true
}).then((result) => {
  if (result.isConfirmed) {
    swalWithBootstrapButtons.fire(
        'کاربر '+title+' شد!',
            'کاربر انتخابی با موفقیت '+title+' شد',
            'success'
            );
            location.href='{{URL::to('/')}}/User/Delete/'+id;
    
  } else if (
    /* Read more about handling dismissals below */
    result.dismiss === Swal.DismissReason.cancel
  ) {
    swalWithBootstrapButtons.fire(
      'لغو شد',
      'عملیات با موفقیت لغو شد',
      'error'
    )
  }
});
    
}
</script>
@endsection