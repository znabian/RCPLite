<div class="card">
    <div class="card-header text-center" >
        <h5>
            معرفی های اخیر
           
        </h5>
        </div>

    <div class="card-body" id="introlist">
        @php
             $introHistory = DB::table('CallTbl AS C')
                    ->join('AppTbl AS A', 'A.Id', '=', 'C.Target')
                    ->where('C.Type', 1)->where('C.UserId',  request('user'))
                    ->select('C.*', 'A.Name')->orderBy('C.Id', 'DESC')->get();
        @endphp
    <div class="table-responsive">
        <table class="table table-striped w-100">
            <thead>
                <tr>
                    <th>نوع ارتباط</th>
                    <th>دوره معرفی شده</th>
                    <th>هدف ارتباط</th>
                    <th>میزان مکالمه</th>
                    {{-- <th>نتیجه ارتباط</th> --}}
                    <th>ارزیابی</th>
                    <th>زمان ارتباط</th>
                    <th>زمان ارتباط بعدی</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @if($introHistory->count())
                    @foreach($introHistory as $first)
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
                            <td>{{ $first->Name }}</td>
                            <td>
                                @if($first->Interaction == 1)
                                    معرفی دوره
                                @elseif($first->Result == 2)
                                    پیشنهاد خرید دوره
                                @else
                                    انتخاب نشده
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
                                @if($first->Assesment == 1)
                                    خوب پیشرفت داشتن مکالمه
                                @elseif($first->Assesment == 2)
                                    خوب پیشرفت نداشتن مکالمه
                                @elseif($first->Assesment == 3)
                                    تمایل به خرید دوره
                                @elseif($first->Assesment == 4)
                                    تمایل نداشتن به خرید دوره
                                @else
                                    دیگر
                                @endif
                            </td>
                            <td>{{ jdate($first->Date) }}</td>
                            <td>{{ jdate($first->Reminder)->format('Y-m-d H:i') }}</td>
                            <td>
                                <a class="btn btn-warning" href="{{route('user.Icall.edit',['user'=>request('user'),'call'=>$first->Id])}}" >
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