
                    <form action="{{(isset($edit_token))?route('payment.token.update',['token'=>$edit_token->Id]):route('payment.token.create',[$payment->Id])}}" method="post" id="frm" enctype="multipart/form-data">
                    @csrf
                    <div class="form-row col-md-12">
                        <div class="col-6">
                            <b class="push-left">بدهی کل:{{ number_format($totalDebt)}} تومان</b>
                        </div>
                        <div class="col-6 mb-3">
                            <b class="push-left">پرداختی کل:{{ number_format($totalPayment)}} تومان</b>
                        </div>
                        <div class="col">
                            <label>نوع پرداخت</label>
                            <select onchange="chlbl(this.value)" class="form-control" name="Type" id="Type">
                                <option value="1">نقدی</option>
                                <option value="2">اقساطی</option>
                            </select>
                        </div>
                        <div class="col">
                            <label>مبلغ پرداختی (تومان)
                                <small style="color:red" id="errors_Price"></small>
                            </label>
                            <input oninput="checkPrice()" class="form-control" id="Price" name="Price">
                        </div>
                        <div class="col">
                            <label id="ddate">تاریخ پرداخت
                                <small style="color:red" id="errors_DeadLine"></small>
                            </label>
                            <input type="text"  class="form-control" style="background: transparent" readonly placeholder="وارد نشده"  id="DeadLine" value="{{(isset($edit_token))?explode(' 0',$edit_token->DeadLine)[0]:''}}">
                            <input  type="hidden" name="DeadLine"   id="DeadLineH" >
                        </div>
                        <div class="col-md-3">
                            <label>رسید تراکنش
                                </label>
                            <input type="file"  class="form-control" name="PaymentImage" accept="image/*" title="عکس رسید پرداختی را اضافه کنید">
                            @isset($edit_token)
                            <img src="{{$edit_token->PaymentImage??''}}" alt="" class="img-thumbnail">
                            @endisset
                        </div>
                        <div class="col-md-3">
                            <label>توضیحات
                                </label>
                            <input type="text"  class="form-control" name="Description" id="TransactionDes"  value="{{(isset($edit_token))?$edit_token->Description:''}}">
                            
                        </div>
                   
                        <div class="col-md-1 mt-4">
                            @isset($edit_token)
                            <button type="button" id="pbtn"  onclick="submitForm()" class="btn float-left btn-success ">                       
                               ثبت 
                            </button>
                            @else
                            
                            <button type="button" id="pbtn" disabled onclick="submitForm()" class="btn float-left {{($saveToken)?'disabled" style="background: #777':'btn-success'}} ">                       
                                {{($saveToken)?"تکمیل":"ثبت"}} 
                            </button>
                            @endisset
                        </div>
                    </div>
                        
                    </form>
                       
