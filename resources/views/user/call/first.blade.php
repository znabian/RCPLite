<div class="form-row">  
    <div class="col">
        <label>نوع ارتباط
            <small style="color:red" id="errors_ConnectionType_f"></small>
        </label>
        <select class="form-control" name="ConnectionType" id="ConnectionType_f">
            <option value="0" disabled selected>انتخاب نشده</option>
            <option value="1">تماس تلفنی</option>
            <option value="2">واتساپ</option>
            <option value="10">دیگر</option>
        </select>
    </div>   
    <div class="col">
        <label>هدف ارتباط
            <small style="color:red" id="errors_Target_f"></small>
        </label>
        <select class="form-control" name="Target" id="Target_f">
            <option value="0" disabled selected>انتخاب نشده</option>
            <option value="1">ارسال لینک استعدادیابی</option>
            <option value="2">ارسال لینک سواد یونسکو</option>
            <option value="3">تحلیل استعدادیابی</option>
            <option value="4">تکمیل اطلاعات</option>
            <option value="5">تحلیل سنجش سواد</option>
            <option value="6">اهمیت استعدادیابی</option>
            <option value="7">پیگیری نتایج فایل ها</option>
            <option value="8">بررسی نتیجه معرفی دوره</option>
            <option value="9">پیگیری های دیگر</option>
            <option value="10">دیگر</option>
            
        </select>
    </div>
        <div class="col">
            <label>میزان مکالمه(دقیقه)
                <small style="color:red" id="errors_Duration_f"></small>
            </label>
            <input type="number" min="0" class="form-control" name="Duration" id="Duration_f">
        </div>
   
    
</div>
<div class="form-row">
            <input type='hidden' name="Result" value="1"/>
            <input type='hidden' name="Type" value="0"/>
            <input type='hidden' name="NextIntroDate" id="NextDate2" />
            <input type='hidden' name="UserId"  value="{{request('user')}}"/>

        <div class="col">
            <label>میزان تعامل
                <small style="color:red" id="errors_Interaction_f"></small>
            </label>
            <select class="form-control" name="Interaction" id="Interaction_f" onchange="showdivs(this.value)">
                <option value="0" disabled selected>انتخاب نشده</option>
                <option value="1">تمایل داشت</option>
                <option value="2">نیاز به مشاوره بیشتر</option>
                <option value="3">تمایل نداشت</option>
                <option value="4">نا مشخص</option>
            </select>
        </div>
        
            <div class="col d-none" id="int1" >
                <label>دلیل تعامل داشتن
                    <small style="color:red" id="errors_InteractionResult1"></small>
                </label>
                <select class="form-control" name="InteractionResult" id="InteractionResult1">
                    <option value="0" disabled selected>انتخاب نشده</option>
                    <option value="1">شناخت و تعریف</option>
                    <option value="2">نیاز واقعی</option>
                </select>
            </div>

            <div class="col d-none" id="int2" >
                <label>دلیل نیاز به مشاوره
                    <small style="color:red" id="errors_InteractionResult2"></small>
                </label>
                <select class="form-control" name="InteractionResult" id="InteractionResult2">
                    <option value="0" disabled selected>انتخاب نشده</option>
                    <option value="1">شرایط زمانی نامساعد</option>
                    <option value="2">نیاز به مشورت</option>
                    <option value="3">عدم وجود دغدغه</option>
                    <option value="4">عدم آشنایی با محصول</option>
                    <option value="5">قیمت</option>
                </select>
            </div>

            <div class="col d-none" id="int3" >
                <label>دلیل عدم تمایل
                    <small style="color:red" id="errors_InteractionResult3"></small>
                </label>
                <select class="form-control"  name="InteractionResult" id="InteractionResult3">
                    <option value="0" disabled selected>انتخاب نشده</option>
                    <option value="1">عدم شناخت برند</option>
                    <option value="2">عدم تناسب محصول با مخاطب</option>
                    <option value="3">مشکل در لینک</option>
                    <option value="4">مشکل در آزمون</option>
                    <option value="5">عدم رضایت</option>
                    <option value="6">قیمت</option>
                </select>
            </div>
        <div class="col">
            <label>تاریخ تماس بعدی</label>
             <input class="form-control" placeholder="وارد نشده" autocomplete="off" id="NextIntroDate2" readonly style="background: transparent" value="{{(request('edit'))?explode(' ',DB::table('CallTbl')->find(request('edit'))->Reminder)[0]:''}}">
        </div>
        <div class="col">
            <label>ساعت تماس بعدی</label>
            <input class="form-control" type="time" name="Time" id="Time_f">
        </div>
</div>
<div class="form-row">
            <div class="col">
                <label>خلاصه تماس</label>
                <textarea class="form-control" rows="4" id='Description_f' name="Description"></textarea>
            </div>                
</div> 