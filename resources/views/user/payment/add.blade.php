
                    <form action="{{($edit)?route('payment.update',[$edit->Id]):route('payment.create',[$user->Id])}}" method="post" id="frm">
                    @csrf
                    @if($edit)
                    <input type="hidden" name="id" value="{{$edit->Id}}">
                    @endif
                    <div class="form-row col-md-12">
                        <div class="form-group col-md-3">
                            <label>انتخاب دوره</label>
                            <select onchange="appPrice()" class="form-control" name="AppId" id="AppId">
                                <option value="">انتخاب کنید</option>
                                @foreach ($apps as $app)
                                <option value="{{$app->Id}}">{{ $app->Name }}</option>
                                    
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label>مبلغ مصوب محصول (تومان)</label>
                            <input  class="form-control" disabled style="color: rgb(150, 150, 150)"  readonly id="DefaultPrice">
                            <input  type="hidden" name="DefaultPrice" id="DefaultPrice2">
                        </div>
                        <div class="form-group col-md-3">
                            <label>  مبلغ فروخته شده (تومان)
                                <small style="color:red" id="errors_Price"></small>
                            </label>
                            <input  class="form-control" name="SupportPrice" min="0" id="SupportPrice"  oninput="checkPrice()" >
                        </div>
                        @if($edit)
                        <div class="form-group col-md-12">
                            <button type="button" id="pbtn" onclick="submitForm()" class="btn btn-success float-left">                       
                                بروزرسانی </button>
                        </div>
                        @else
                        <div class="form-group col-md-12">
                            <button type="button" id="pbtn" disabled onclick="submitForm()" class="btn btn-success float-left">                       
                                ثبت </button>
                        </div>
                        @endif
                    </div>
                        
                    </form>
                       
