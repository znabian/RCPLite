@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center" dir="rtl">
        <div class="col-md-12 mb-3">
            <h4>
                    <span class="float-right">
                        <i class="fa fa-user"></i>
                    {{$user->Name.' '.$user->Family}}
                    </span>
                <button onclick="back()" class="btn btn-danger float-left">
                    بازگشت به کاربر
                <i class="fa fa-angle-left"></i>
                </button>
                </h4>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-header text-center">
                    <h5>
                        خرید دوره                 
                       
                    </h5>
                    </div>

                <div class="card-body text-right">
                    @include('user.payment.add')
                </div>
            </div>
        </div>
        <div class="col-md-12 mt-3">
            <div class="card">
                <div class="card-header text-center">
                    <h5>
                        دوره های خریداری شده                       
                       
                    </h5>
                    </div>

                <div class="card-body">
                    <div class="table-responsive">
                    <table class="table table-striped text-center">
                        <thead>
                            <tr>
                                <th>دوره</th>
                                <th>مبلغ فروخته شده</th>
                                <th>بدهی</th>
                                <th>وضعیت انتظار</th>
                                <th>وضعیت حساب</th>
                                <th>زمان</th>
                                <th>تراکنش ها</th>
                                <th>ویرایش دوره</th>
                                <th>حذف دوره</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                            @foreach($fiscalHistory as $history)
                            <tr>
                                <td>{{ $history->Product->App->Name }}</td>
                                <td>{{ number_format($history->SupportPrice) }}</td>
                                <td>{{  number_format($history->LeftOver) }}</td>
                                <td>
                                    @if($history->Confirm == 0)
                                        <span style="color: red;">غیر مجاز</span>
                                    @elseif($history->Confirm == 1)
                                        <span style="color: green;">مجاز</span>
                                    @else
                                        <span style="color: blue;">در انتظار تایید</span>
                                    @endif
                                </td>
                                <td>
                                    @if($history->Status == 0)
                                        <span style="color: blue;">در حال تسویه</span>
                                    @elseif($history->Status == 1)
                                        <span style="color: green;">تسویه شده</span>
                                    @endif
                                </td>
                                <td>{{ jdate($history->Date) }}</td>
                                <td>
                                    @if($history->Confirm == 1)
                                        @if($history->Status == 0)
                                        <a href="{{route('payment.tokens',['user'=>$user->Id,'payment'=>$history->Id])}}" class="btn btn-primary">
                                            <i class="fa fa-eye"></i>
                                            مشاهده</a>
                                        @elseif($history->Status == 1)
                                        <a href="{{route('payment.tokens',['user'=>$user->Id,'payment'=>$history->Id])}}" class="btn btn-success">
                                            <i class="fa fa-eye"></i>
                                            مشاهده</a>
                                        @endif
                                    @elseif($history->Confirm == 0)
                                    <a href="{{route('payment.tokens',['user'=>$user->Id,'payment'=>$history->Id])}}" class="btn btn-secondary" >
                                        <i class="fa fa-eye"></i>
                                        مشاهده</a>
                                    @else
                                    <a href="{{route('payment.tokens',['user'=>$user->Id,'payment'=>$history->Id])}}" class="btn btn-warning">
                                        <i class="fa fa-eye"></i>
                                        مشاهده</a>
                                    @endif
                                </td>
                                <td>
                                    @if(Auth::user()->Id == 8 || Auth::user()->Id == 32658)
                                    <a href="{{route('payment.edit',['user'=>$user->Id,'payment'=>$history->Id])}}" class="btn btn-warning">
                                        <i class="fa fa-edit"></i>
                                        ویرایش</a>
                                    @elseif($history->Confirm == 1)
                                        <span class="btn disabled" style="background: #777">
                                            <i class="fa fa-times-circle"></i>
                                            غیر مجاز</span>
                                    @else
                                    <a href="{{route('payment.edit',['user'=>$user->Id,'payment'=>$history->Id])}}" class="btn btn-warning">
                                        <i class="fa fa-edit"></i>
                                        ویرایش</a>
                                    @endif
                                </td>
                                <td>
                                    <button onclick="delpayment('{{route('payment.delete',['user'=>$user->Id,'payment'=>$history->Id])}}','{{$history->Product->App->Name }}')" class="btn btn-danger">
                                        <i class="fa fa-trash"></i>
                                        حذف</button>
                                </td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('script')
<script>
    function  appPrice() 
    {
        if(AppId.value)
        {
            window.axios.post("{{route('app.price')}}", {AppId:AppId.value, UserId: '{{$user->Id}}'})
                .then(function ({data}) {
                    if (data.success) {
                        DefaultPrice.value = data.data.Total.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                        SupportPrice.value = data.data.Total.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                        pbtn.disabled=false;
                        
                    }
                    else {
                        Swal.fire({
                            icon: 'error',
                            title: 'خطا!',
                            text: data.message
                         });
                DefaultPrice.value = SupportPrice.value =AppId.value = "";
                pbtn.disabled=true;
                    }
                })
                .catch(error => {
                    console.log(error)
                        Swal.fire({
                            icon: 'error',
                            title: 'خطا!',
                            text: 'خطایی رخ داده مجددا امتحان نمایید'
                         });
                DefaultPrice.value = SupportPrice.value = "";
                pbtn.disabled=true;
                });
            }
            else
            {
                DefaultPrice.value = SupportPrice.value = "";
                pbtn.disabled=true;
            }
            errors_Price.innerHTML ='';
    }
    function checkPrice()
        {
        SupportPrice.value = SupportPrice.value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        
    }
        
    function submitForm(){
        if (validationForm()) 
        {
            SupportPrice.value=SupportPrice.value.replace(/\,/g,'');
                DefaultPrice.value=DefaultPrice.value.replace(/\,/g,'');
                DefaultPrice2.value=DefaultPrice.value;
            
            pbtn.disabled=true;
            frm.submit();
            
        }
    }
    function back(){
        window.location.href="{{$backUrl}}";
    }
    function validationForm()
        {
            var SPrice=parseInt(SupportPrice.value.replace(/\,/g,''));
        var DPrice=parseInt(DefaultPrice.value.replace(/\,/g,''));
        
        if (SPrice <= DPrice ) {// this.admin == 32658 || this.admin == 8) {
            return true;
        }
        else
        {
            errors_Price.innerHTML = 'غیر مجاز میباشد';
        }

    }
    function delpayment(url,name)
    {
        const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
        })

        swalWithBootstrapButtons.fire({
        title:"حذف دوره "+name,
        text:  'دوره مورد نظر حذف شود؟',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText:  'بله, حذف شود',
        cancelButtonText: 'لغو عملیات',
        reverseButtons: true
        }).then((result) => {
        if (result.isConfirmed) {
            swalWithBootstrapButtons.fire(
                'دوره '+name+' شد!',
                    'دوره انتخابی با موفقیت حذف شد',
                    'success'
                    );
                    location.href=url;
            
        } else if (
            /* Read more about handling dismissals below */
            result.dismiss === Swal.DismissReason.cancel
        ) {
            swalWithBootstrapButtons.fire(
            'لغو عملیات',
            'عملیات با موفقیت لغو شد',
            'error'
            )
        }
        });
            
    }
    @if($edit)
    AppId.value="{{$edit->AppId}}";
    DefaultPrice.value="{{number_format($edit->DefaultPrice)}}";
    SupportPrice.value="{{number_format($edit->SupportPrice)}}";
    @endif
</script>
@endsection