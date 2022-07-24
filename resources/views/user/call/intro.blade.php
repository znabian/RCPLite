<div class="form-row">  
    <div class="col">
        <label>نوع ارتباط
            <small style="color:red" id="errors_ConnectionType"></small>
        </label>
        <select class="form-control" name="ConnectionType" id="ConnectionType">
            <option value="0" disabled selected>انتخاب نشده</option>
            <option value="1">تماس تلفنی</option>
            <option value="2">واتساپ</option>
            <option value="10">دیگر</option>
        </select>
    </div>   
    <div class="col">
        <label>دوره معرفی شده
            <small style="color:red" id="errors_Target"></small>
        </label>
        <select class="form-control" name="Target" id="Target">
            <option value="0">انتخاب نشده</option>
            @php
                $apps=DB::table('AppTbl')->where('Parent', 0)->where('Active', 1)->get();
            @endphp
            @foreach ($apps as $app)
                <option  value="{{$app->Id}}">{{ $app->Name }}</option>
            @endforeach
            
        </select>
    </div>
    <div class="col">
        <label>هدف ارتباط
            <small style="color:red" id="errors_Interaction"></small>
        </label>
        <select class="form-control" name="Interaction" id="Interaction" onchange="if(this.value>0){linkdiv.classList.remove('d-none')} else{linkdiv.classList.add('d-none')}">
            <option value="0">انتخاب نشده</option>
            <option value="1">معرفی دوره</option>
            <option value="2">پیشنهاد خرید دوره</option>
        </select>
    </div>
    {{-- <div class="interaction" v-if="Interaction > 0"> --}}
        <div class="col d-none" id="linkdiv" >
            <label>لینک مربوطه</label>
            <div style="display: flex; justify-content: space-between; align-items: center; width: 100%; max-width: 100%;">
                <input type="text" id="link" disabled selected readonly class="form-control" value="https://erfankhoshnazar.com/download">
                <i onclick="copyClipboard()" title="کپی" class=" btn fa fa-clipboard"></i>
            </div>
        </div>
</div>
<div class="form-row">
        <div class="col">
            <label>میزان مکالمه(دقیقه)
                <small style="color:red" id="errors_Duration"></small>
            </label>
            <input type="number" min="0" class="form-control" name="Duration" id="Duration">
        </div>
            <input type='hidden' name="Result" value="1"/>
            <input type='hidden' name="Type" value="1"/>
            <input type='hidden' name="NextIntroDate" id="NextDate" />
            <input type='hidden' name="UserId" id="UserId" value="{{request('user')}}"/>

        <div class="col">
            <label>ارزیابی
                <small style="color:red" id="errors_Assesment"></small>
            </label>
            <select class="form-control" name="Assesment" id="Assesment">
                <option value="0">انتخاب نشده</option>
                <option value="1">خوب پیشرفت داشتن مکالمه</option>
                <option value="2">خوب پیشرفت نداشتن مکالمه</option>
                <option value="3">تمایل به خرید دوره</option>
                <option value="4">تمایل نداشتن به خرید دوره</option>
                <option value="10">دیگر</option>
            </select>
        </div>
        <div class="col">
            <label>تاریخ تماس بعدی</label>
             <input class="form-control" placeholder="وارد نشده" autocomplete="off" id="NextIntroDate" readonly style="background: transparent" value="{{(request('call'))?explode(' ',DB::table('CallTbl')->find(request('call'))->Reminder)[0]:''}}">
        </div>
        <div class="col">
            <label>ساعت تماس بعدی</label>
            <input class="form-control" type="time" name="Time" id="Time">
        </div>
</div>
<div class="form-row">
            <div class="col">
                <label>خلاصه تماس</label>
                <textarea class="form-control" rows="4" id='Description' name="Description"></textarea>
            </div>                
</div> 