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
                    بازگشت به تاریخچه خرید
                <i class="fa fa-angle-left"></i>
                </button>
                </h4>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-header text-center">
                    <h5>
                        توضیحات دوره        
                       
                    </h5>
                    </div>

                <div class="card-body text-right">
                   <div class="form-row">
                    <div class="col-9">
                        <label >توضیحات</label>
                        <input type="text" class="form-control" name="Description" value="{{$payment->Description}}" id="Description">
                    </div>
                    <div class="col">
                        <label >جایگاه</label>
                        <input type="number" min='0' class="form-control" value="{{$payment->Place}}"  name="Place" id="Place">
                    </div>
                    <div class="col mt-4">
                        <label for=""></label>
                        <input type="button" class="btn btn-success pull-left" value="ثبت">
                    </div>
                   </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 mt-3">
            <div class="card">
                <div class="card-header text-center">
                    <h5>
                        @isset($edit_token)
                        ویرایش تراکنش
                        @else
                        تراکنش جدید                 
                       @endisset
                    </h5>
                    </div>

                <div class="card-body text-right">
                    @include('user.payment.token.add')
                </div>
            </div>
        </div>
        <div class="col-md-12 mt-3">
            <div class="card">
                <div class="card-header text-center">
                    <h5>
                        تراکنش های اخیر                    
                       
                    </h5>
                    </div>

                <div class="card-body">
                    <div class="table-responsive">
                    <table class="table table-striped text-center">
                        <thead>
                            <th>نوع پرداخت</th>
                            <th>مبلغ پرداختی</th>
                            {{-- <th>مبلغ باقی مانده</th> --}}
                            <th>وضعیت پرداخت</th>
                            <th>تاریخ سر رسید</th>
                            <th>تاریخ پرداختی</th>
                            <th>عکس رسید</th>
                            <th>تسویه تراکنش</th>
                            <th>ویرایش تراکنش</th>
                            <th>حذف تراکنش</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($tokens as $token)
                            <tr>
                                <td>
                                    @if($token->Type == 1)
                                        <span>نقدی</span>
                                    @else
                                        <span>اقساطی</span>
                                    @endif
                                </td>
                                <td>{{ number_format($token->Price) }}</td>
                                {{-- <td>{{ number_format($token->LeftOver) }}</td> --}}
                                <td>
                                    @if($token->Status == 1)
                                        <span>تسویه شده</span>
                                    @else
                                        <span>در انتظار تسویه</span>
                                    @endif
                                </td>
                                <td>{{ jdate($token->DeadLine)->format('Y-m-d') }}</td>

                                <td>{{ jdate($token->Collection)->format('Y-m-d') == '1278-10-11' || jdate($token->Collection)->format('Y-m-d') == '1348-10-11' ? 'تسویه نشده' : jdate($token->Collection)->format('Y-m-d') }}</td>
                                <td>
                                    @if(isset($token->PaymentImage))
                                        <span style="color: green;">ثبت شده</span>
                                    @else
                                        <span style="color: red;">ثبت نشده</span>
                                    @endif
                                </td>
                                @if($token->Status == 0)
                                @if(jdate($token->DeadLine)->format('Y-m-d')<jdate()->format('Y-m-d'))
                                <td>
                                    <span onclick="dshow('{{ $token->Id }}');" data-toggle="modal" data-target="#myFirstDialog" class="btn btn-primary" >
                                        <i class="fa fa-check"></i>
                                        تسویه</span></td>
                                @else
                                    <td><a class="btn btn-primary"  href="{{route('payment.token.complete',[$token->Id])}}">
                                        <i class="fa fa-check"></i>
                                        تسویه</a></td>
                                @endif    
                                    <td><a class="btn btn-warning"  href="{{route('payment.token.edit',[$token->Id])}}">
                                        <i class="fa fa-edit"></i>
                                        ویرایش</a></td>
                                @else
                                    <td><span class="btn btn-success disabled">
                                        <i class="fa fa-check"></i>
                                        تکمیل</span></td>
                                    <td><span class="btn disabled" style="background: #777">
                                        <i class="fa fa-times-circle"></i>
                                        غیر مجاز</span></td>
                                @endif
                                <td>
                                        <button onclick="deltoken('{{route('payment.token.delete',[$token->Id])}}')" class="btn btn-danger">
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


  
  <!-- Modal -->
  <div class="modal fade" id="myFirstDialog" tabindex="-1" role="dialog" aria-labelledby="myFirstDialogLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="myFirstDialogLabel">تسویه تراکنش</h5>
          <button type="button" class="btn pull-left" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body text-right">
            <form  method="get" class="form">
                @csrf
                <div class="form-row">
                    <div class="col">
                    <label for="PDate">تاریخ پرداخت
                    </label>
                    <input class="form-control" id="PDate"  readonly style="background-color: white;">
                    <input class="form-control" id="tid" type="hidden" value="">
                    </div>
              </div>
            </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
         <button type="button" class="btn btn-success" onclick="submitpayment();">ثبت پرداخت</button>  
        </div>
      </div>
    </div>
  </div>
@endsection
@section('script')
<script>
        var PD;
        @isset($edit_token)
        Type.value="{{$edit_token->Type}}";
        DeadLineH.value='{{$edit_token->DeadLine}}';
        Price.value="{{number_format($edit_token->Price)}}";
        @endisset
        
    function deltoken(url)
    {
        const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
        })

        swalWithBootstrapButtons.fire({
        title:"حذف تراکنش",
        text:  'تراکنش مورد نظر حذف شود؟',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText:  'بله, حذف شود',
        cancelButtonText: 'لغو عملیات',
        reverseButtons: true
        }).then((result) => {
        if (result.isConfirmed) {
            swalWithBootstrapButtons.fire(
                'تراکنش شد!',
                    'تراکنش انتخابی با موفقیت حذف شد',
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
        function submitForm(){
            if (validationForm()) 
            {
                Price.value=Price.value.replace(/\,/g,'');
                
                pbtn.disabled=true;
                frm.submit();
               
            }
            else
            pbtn.disabled=false;
        }
        function back(){
            window.location.href="{{$backUrl}}";
        }
        function validationForm() {
             var tP=parseInt(Price.value.replace(/\,/g,''));
            if ((tP > 0 && DeadLineH.value) || {{auth()->user()->Id}} == 32658 || {{auth()->user()->Id}} == 8) {
                return true;
            }
            if (tP <= 0)  errors_Price.innerHTML = 'الزامی است';
            if (!DeadLineH.value)  errors_DeadLine.innerHTML= 'الزامی است';            

        }
        function chlbl(type)
        {
            if(type==2)
            document.getElementById('ddate').innerHTML='تاریخ سررسید';
            else 
            document.getElementById('ddate').innerHTML='تاریخ پرداخت';
            
        }
        function checkPrice() 
        {
            var tP = Price.value.replace(/\,/g,'');
            Price.value= Price.value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            if ({{auth()->user()->Id}} != 8 && {{auth()->user()->Id}} != 32658) {
                if (tP.length >= 5) {
                    window.axios.post("{{route('payment.checkPrice')}}", {
                        Price: tP,
                        PaymentId: {{$payment->Id}}
                    })
                        .then(function ({data}) {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'error',
                                    title:"تراکنش غیر مجاز",
                                    text:"مبلغ وارد شده بیشتر از بدهی کاربر است"
                                });
                                pbtn.disabled=true;
                            } else {
                                pbtn.disabled=false;

                            }
                        })
                        .catch(error => {
                            console.log(error)
                            Swal.fire({
                                    icon: 'error',
                                    title: '',
                                    text:'خطایی رخ داده مجددا امتحان نمایید'
                                });
                                pbtn.disabled=true;
                        });
                }
            }
        }
        $(document).ready(function() {  
            $("#DeadLine").persianDatepicker({
                format: 'D MMMM YYYY',
                observer: true,
                autoClose: true,
                initialValue: "{{isset($edit_token)?'1':'0'}}",
                onSelect: function(unix){
                    DeadLineH.value  = unix;
                }
                });
                dialog.close();
            });
        function dshow(tid)
        { 
            document.getElementById('tid').value=tid;
            $('#PDate').persianDatepicker({
                    format: 'D MMMM YYYY',
                    observer: true,
                    autoClose: true,
                    
                    onSelect: function(unix){
                    PD = unix;
                    },
                });

        }
        function dclose()
        {
        var dialog = document.getElementById('myFirstDialog');   
        dialog.close();   
        }
        
        function submitpayment()
        { tid=document.getElementById('tid').value;
            var url="{{URL::to('/')}}/Transaction/"+tid+'/complete?date='+PD;
            document.location.href =url;     

        }
</script>
@endsection