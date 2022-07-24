<div class="card mb-3">
    <div class="card-header text-center" >
        <h5>
            ارتباطات اخیر
           
        </h5>
        </div>

    <div class="card-body" id="calllist">
        @php
            $firstHistory = DB::table('CallTbl')->where('Type', 0)->where('UserId', request('user'))->orderBy('Id', 'DESC')->get();
               
            $target=['','ارسال لینک استعدادیابی','ارسال لینک سواد یونسکو','تحلیل استعدادیابی','تکمیل اطلاعات',
            'تحلیل سنجش سواد','اهمیت استعدادیابی','پیگیری نتایج فایل ها','بررسی نتیجه معرفی دوره','پیگیری های دیگر','دیگر'];
        
        @endphp
    <div class="table-responsive">
        <table class="table table-striped w-100">
            <thead>
                <tr>
                    <th>نوع ارتباط</th>
                    <th>هدف ارتباط</th>
                    <th>میزان مکالمه</th>
                    <th>میزان تعامل</th>
                    <th>دلیل</th>
                    <th>زمان ارتباط</th>
                    <th>زمان ارتباط بعدی</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @if($firstHistory->count())
                @foreach($firstHistory as $first)               
                <tr>
                    <td>
                        @if($first->ConnectionType == 0)
                            انتخاب نشده
                        @elseif($first->ConnectionType == 1)
                            تماس تلفنی
                        @elseif($first->ConnectionType == 2)
                            واتساپ
                        @else
                            دیگر
                        @endif
                    </td>
                    <td>
                        @if($first->Target == 0)
                            انتخاب نشده
                        @elseif($first->Target == 1)
                            ارسال لینک استعدادیابی
                        @elseif($first->Target == 2)
                            ارسال لینک سواد یونسکو
                        @elseif($first->Target == 3)
                            تحلیل استعدادیابی
                        @elseif($first->Target == 4)
                            تکمیل اطلاعات
                        @elseif($first->Target == 5)
                            تحلیل سنجش سواد
                        @elseif($first->Target == 6)
                            اهمیت استعدادیابی
                        @elseif($first->Target == 7)
                            پیگیری نتایج فایل ها
                        @elseif($first->Target == 8)
                            بررسی نتیجه معرفی دوره
                        @elseif($first->Target == 9)
                            پیگیری های دیگر
                        @else
                            دیگر
                        @endif
                    </td>
                    <td>{{ $first->Duration }} دقیقه</td>
                    {{-- <td>
                        @if($first->Result == 0)
                            انتخاب نشده
                        @elseif($first->Result == 1)
                            موفق
                        @else
                            ناموفق
                        @endif
                    </td> --}}
                    <td>
                        @if($first->Interaction == 0)
                            انتخاب نشده
                        @elseif($first->Interaction == 1)
                            تمایل داشت
                        @elseif($first->Interaction == 2)
                            نیاز به مشاوره بیشتر
                        @elseif($first->Interaction == 3)
                            تمایل نداشت
                        @else
                            نامشخص
                        @endif
                    </td>
                    <td>
                        @if($first->Interaction == 0)
                            انتخاب نشده
                        @elseif($first->Interaction == 1)
                            @if($first->InteractionResult == 0)
                                انتخاب  نشده
                            @elseif($first->InteractionResult == 1)
                                شناخت و تعریف
                            @else
                                نیاز واقعی
                            @endif
                        @elseif($first->Interaction == 2)
                            @if($first->InteractionResult == 0)
                                انتخاب  نشده
                            @elseif($first->InteractionResult == 1)
                                شرایط زمانی نامساعد
                            @elseif($first->InteractionResult == 2)
                                نیاز به مشورت
                            @elseif($first->InteractionResult == 3)
                                عدم وجود دغدغه
                            @elseif($first->InteractionResult == 4)
                                عدم آشنایی با محصول
                            @else
                                قیمت
                            @endif
                        @elseif($first->Interaction == 3)
                            @if($first->InteractionResult == 0)
                                انتخاب  نشده
                            @elseif($first->InteractionResult == 1)
                                عدم شناخت برند
                            @elseif($first->InteractionResult == 2)
                                عدم تناسب محصول با مخاطب
                            @elseif($first->InteractionResult == 3)
                                مشکل در لینک
                            @elseif($first->InteractionResult == 4)
                                مشکل در آزمون
                            @elseif($first->InteractionResult == 5)
                                عدم رضایت
                            @else
                                قیمت
                            @endif
                        @else
                            نامشخص
                        @endif
                    </td>
                    <td>{{ jdate($first->Date) }}</td>
                    <td>{{ jdate($first->Reminder)->format('Y-m-d H:i') }}</td>
                    <td>
                        <a class="btn btn-warning" href="{{route('user.Fcall.edit',['user'=>request('user'),'edit'=>$first->Id])}}" >
                        ویرایش
                        </a>
                    </td>
                </tr>               
                
                @endforeach
                @else
                <tr>
                    <td colspan="8" class="text-center">
                        تماسی ثبت نشده است
                    </td>
                </tr>
                @endif

            </tbody>
        </table>

        </div>
    </div>
        
</div>