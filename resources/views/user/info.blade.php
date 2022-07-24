                <div class="card-header text-center">
                    <h5>
                        اطلاعات تکمیلی 
                    </h5>
                    </div>

                <div class="card-body">
                    <form action="{{route('user.Detail',[$user->Id])}}" method="post" id="frmdet">
                    @csrf
                    <div class="form-row col-md-12">
                        <div class="form-group col-md-3">
                                <label>کانال ورودی</label>
                                <select class="form-control" name="Platform" id="Platform">
                                    <option value="0" disabled selected>انتخاب نشده</option>
                                    <option value="1">استوری اینستاگرام</option>
                                    <option value="2">مدیا اد</option>
                                    <option value="3">یکتانت</option>
                                    <option value="4">بیو اینستاگرام</option>
                                    <option value="5">واتساپ</option>
                                    <option value="6">دایرکت اینستاگرام</option>
                                    <option value="7">گوگل ads</option>
                                    <option value="8">آپارات</option>
                                    <option value="9">وبسایت مستقیم</option>
                                    <option value="10">تلگرام</option>
                                    <option value="11">sms</option>
                                    <option value="12">تبلیغات اینستاگرام</option>
                                    <option value="13">صباویژن</option>
                                    <option value="14">لینکدین</option>
                                    <option value="15">یوتیوب</option>
                                    <option value="16">دستی</option>
                                </select>
                            
                        </div>
                        <div class="form-group col-md-3">
                            <label>تاریخ تولد پدر</label>
                          <input class="form-control" id="FatherBD" name="FatherBirthDay" autocomplete="off" readonly style="background: transparent" placeholder="وارد نشده" value="{{($user->Detail->FatherBirthDay)?\Morilog\Jalali\CalendarUtils::createCarbonFromFormat('Y-m-d', $user->Detail->FatherBirthDay):null}}">
                        </div>
                        <div class="form-group col-md-3">
                            <label>تاریخ تولد مادر</label>
                            <input class="form-control" id="MotherBD" name="MotherBirthDay" style="background: transparent" autocomplete="off" readonly  placeholder="وارد نشده" value="{{($user->Detail->MotherBirthDay)?\Morilog\Jalali\CalendarUtils::createCarbonFromFormat('Y-m-d', $user->Detail->MotherBirthDay):null}}">
                        </div>
                        <input type="hidden" id="MBD" name="MBD">
                        <input type="hidden" id="FBD" name="FBD">
                    {{-- </div>
                    <div class="form-row "> --}}
                        <div class="form-group  col-md-3">
                            <label>تعداد فرزند</label>
                          <input type="number" class="form-control" min="0" max="10" name="ChildCount" id="ChildCount">
                        </div>
                        <div class="form-group col-md-3">
                            <label>رابطه با همسر</label>
                            <select class="form-control" name="SpouseRelation" id="SpouseRelation">
                                <option value="0" disabled selected>انتخاب نشده</option>
                                <option value="1">بد</option>
                                <option value="2">مناسب</option>
                                <option value="3">خوب</option>
                                <option value="4">مطلقه</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label>وضعیت مالی</label>
                            <select class="form-control" name="FinancialStatus" id="FinancialStatus">
                                <option value="0" disabled selected>انتخاب نشده</option>
                                <option value="1">ضعیف (0 الی 5 میلیون)</option>
                                <option value="2">عادی (6 الی 10 میلیون)</option>
                                <option value="3">مناسب (11 الی 15 میلیون)</option>
                                <option value="4">خوب (16 الی 20 میلیون)</option>
                                <option value="5">عالی (بالای 20 میلیون)</option>
                            </select>
                        </div>
                        
                        <div class="form-group col-md-3">
                            <label>استان</label>
                            <input type="text" class="form-control" name="Province" id="Province">
                        </div>
                        <div class="form-group col-md-3">
                            <label>شهرستان</label>
                            <input type="text" class="form-control" name="City"  id="City" >
                        </div>
                        <div class="form-group col-md-3">
                            <label>تحصیلات پدر</label>
                            <select class="form-control" name="FatherEducation" id="FatherEducation">
                                <option value="0" disabled selected>انتخاب نشده</option>
                                <option value="1">بی سواد</option>
                                <option value="2">خواندن و نوشتن</option>
                                <option value="3">ابتدایی</option>
                                <option value="4">سیکل</option>
                                <option value="5">دیپلم</option>
                                <option value="6">فوق دیپلم</option>
                                <option value="7">لیسانس</option>
                                <option value="8">فوق لیسانس</option>
                                <option value="9">دکتری به بالا</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label>تحصیلات مادر</label>
                            <select class="form-control" name="MotherEducation" id="MotherEducation">
                                <option value="0" disabled selected>انتخاب نشده</option>
                                <option value="1">بی سواد</option>
                                <option value="2">خواندن و نوشتن</option>
                                <option value="3">ابتدایی</option>
                                <option value="4">سیکل</option>
                                <option value="5">دیپلم</option>
                                <option value="6">فوق دیپلم</option>
                                <option value="7">لیسانس</option>
                                <option value="8">فوق لیسانس</option>
                                <option value="9">دکتری به بالا</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label>شغل پدر</label>
                            <input class="form-control"  type="text" name="Fajob"  list="Fjobs" id="Fajob"  placeholder='انتخاب نشده' /> 
                                <datalist id="Fjobs">
                                    @foreach ($jobs as $index=>$item)
                                <option value="{{$item}}" {{(($user->Detail->FatherJob??'')==$index)?"selected":''}}></option>                                         
                                    @endforeach                                   
                                </datalist>
                        </div>
                        <div class="form-group col-md-3">
                            <label>شغل مادر</label>
                            <input class="form-control"  type="text"  list="Mjobs" id="Mojob" name="Mojob"  placeholder='انتخاب نشده' /> 
                                <datalist id="Mjobs">
                                    @foreach ($jobs as $index=>$item)
                                    <option value="{{$item}}" {{(($user->Detail->MotherJob??'')==$index)?"selected":''}}></option>                                         
                                    @endforeach  
                                    
                                </datalist>
                        </div>
                        <div class="form-group col-md-3">
                            <label>نحوه آشنایی</label>
                            <select class="form-control" name="Orientation" id="Orientation">
                                <option value="0" disabled selected>انتخاب نشده</option>
                                <option value="1">اینستاگرام</option>
                                <option value="2">وبسایت</option>
                                <option value="3">تلگرام</option>
                                <option value="4">دهان به دهان</option>
                                <option value="5">عدم شناخت</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3 d-none">                            
                            <label>خانواده</label>
                            <div style="width: 100%; max-width: 100%;">
                                <input type="number" class="form-control"  oninput="searchPhone()"  >
                              
                            </div>
                        </div>
                    </div>
                    
                    </div>
                    <div class="card-footer">
                        <button type="button" onclick="frmdet.submit()" class="btn btn-success">                       
                            ثبت تغییرات</button>
                        
                    </form>
                        <button onclick="back()" class="btn btn-danger float-left">بازگشت</button>
                </div>

