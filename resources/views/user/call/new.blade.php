@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center" dir="rtl">
        <div class="col-12 mb-3">
            <h4>
                <span class="float-right">
                    <i class="fa fa-user"></i>
                 {{DB::table('UserTbl')->find(request('user'))->Name.' '.DB::table('UserTbl')->find(request('user'))->Family}}</span>
            <button onclick="back()" class="btn btn-danger float-left">
                بازگشت به کاربر
            <i class="fa fa-angle-left"></i>
            </button>
            </h4>
            

        </div>
        <div class="col-md-6 row text-right" >
            @include('user.call.first_list')
        </div>
        <div class="col-md-6 text-right mb-3" >
             @include('user.call.intro_list') 
         
        </div>
        <div class="col-md-6 text-right" >
            <div class="card mb-3">
                <div class="card-header text-center" onclick="callform.classList.toggle('d-none');">
                    <h5 style="cursor: pointer;">
                        @if(request('edit'))
                            ویرایش ارتباط
                        @else
                       افزودن ارتباط جدید 
                        @endif
                       <i class="fa fa-plus pull-left"></i>
                    </h5>
                    </div>

                <div class="card-body d-" id="callform">
                    <form action="{{(request('edit'))?route('call.intro.edit'):route('call.intro.add')}}" method="post" id="frm_first">
                        @csrf
                    @if(request('edit'))
                    <input type="hidden" name="id" value="{{request('edit')}}">
                    @endif
                    @include('user.call.first')
                    </form>
                    <div class="form-group mt-3">
                        <button onclick="submitForm(2)" id="sbtn2" class="btn btn-success float-right">{{(request('edit'))?"ویرایش":"ثبت"}} تماس</button>
                        {{-- <button onclick="back()" class="btn btn-danger float-left">بازگشت</button> --}}
                    </div>
                    
                </div>
            </div> 
        </div>
        <div class="col-md-6 text-right" >
            <div class="card">
                <div class="card-header text-center" onclick="introform.classList.toggle('d-none');">
                    <h5 style="cursor: pointer;">
                        @if(request('call'))
                       ویرایش تماس معرفی 
                        @else
                       افزودن تماس معرفی جدید 
                        @endif
                       <i class="fa fa-plus pull-left"></i>
                    </h5>
                    </div>

                <div class="card-body" id="introform">
                    <form action="{{(request('call'))?route('call.intro.edit'):route('call.intro.add')}}" method="post" id="frm_intro">
                        @csrf
                        @if(request('call'))
                        <input type="hidden" name="id" value="{{request('call')}}">
                        @endif
                    @include('user.call.intro')
                    </form>
                    <div class="form-group mt-3">
                        <button onclick="submitForm()" id="sbtn" class="btn btn-success float-right">{{(request('call'))?"ویرایش":"ثبت"}} تماس</button>
                        {{-- <button onclick="back()" class="btn btn-danger float-left">بازگشت</button> --}}
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('script')
<script type="text/javascript">

    $(document).ready(function() {
      $("#NextIntroDate").persianDatepicker({
        format: 'D MMMM YYYY',
        observer: true,
        autoClose: true,
        initialValue:  {{(request('call'))?'1':'0'}},
        onSelect: function(unix){
            NextDate.value = unix;
        }
        });
        $("#NextIntroDate2").persianDatepicker({
        format: 'D MMMM YYYY',
        observer: true,
        autoClose: true,
        initialValue: {{(request('edit'))?'1':'0'}},
        onSelect: function(unix){
            NextDate2.value = unix;
        }
        });
        
        @if(request('edit'))

        @php
        $fcall=DB::table('CallTbl')->find(request('edit'));
        $rm=explode(' ',$fcall->Reminder);
        @endphp

        ConnectionType_f.value="{{$fcall->ConnectionType}}";
        Target_f.value="{{$fcall->Target}}";
        Duration_f.value="{{$fcall->Duration}}";
        Interaction_f.value="{{$fcall->Interaction}}";
        Time_f.value="{{$rm[1]}}";
        Description_f.innerHTML="{{$fcall->Description}}";
        showdivs("{{$fcall->Interaction}}");
        @endif
        
        @if(request('call'))

        @php
        $fcall=DB::table('CallTbl')->find(request('call'));
        $rm=explode(' ',$fcall->Reminder);
        @endphp

        ConnectionType.value="{{$fcall->ConnectionType}}";
        Target.value="{{$fcall->Target}}";
        Duration.value="{{$fcall->Duration}}";
        Interaction.value="{{$fcall->Interaction}}";
        Assesment.value="{{$fcall->Assesment}}";
        Time.value="{{$rm[1]}}";
        Description.innerHTML="{{$fcall->Description}}";
            if(Interaction.value>0)
            linkdiv.classList.remove('d-none')
           
        @endif
       
    });

    function showdivs(val) 
    {
        if(val==1)   
        {
            int1.classList.remove('d-none');
            int2.classList.add('d-none');
            int3.classList.add('d-none');
            @if(request('edit'))
            InteractionResult1.value="{{$fcall->InteractionResult}}";
            @endif
        } 
        else if(val==2)
        {
            int1.classList.add('d-none');
            int2.classList.remove('d-none');
            int3.classList.add('d-none');
            @if(request('edit'))
            InteractionResult2.value="{{$fcall->InteractionResult}}";
            @endif
        }
        else if(val==3)
        {
            int1.classList.add('d-none');
            int2.classList.add('d-none');
            int3.classList.remove('d-none');
            @if(request('edit'))
            InteractionResult3.value="{{$fcall->InteractionResult}}";
            @endif
        }
        else 
        {
            int1.classList.add('d-none');
            int2.classList.add('d-none');
            int3.classList.add('d-none');
        }
           
    }

    function copyClipboard() {
            var copyText = document.getElementById("link");
            copyText.select();
            navigator.clipboard.writeText(copyText.value);
            Swal.fire({
                    icon: 'success',
                    title: '',
                    text:'لینک با موفقیت کپی شد'
                });
           
        }
     function back()
     {
        window.location.href="{{route('user.edit',[request('user')])}}";
     }
    function validationForm_first() 
    {
        if (ConnectionType_f.value  > 0 && Target_f.value  > 0 && //Result > 0 &&
                ((Interaction_f.value  > 0 && (
                    (Interaction_f.value  ==1 && InteractionResult1.value  > 0)||(Interaction_f.value  ==2 && InteractionResult2.value  > 0)||(Interaction_f.value  ==3 && InteractionResult3.value  > 0) )
                ) ||
                (Interaction_f.value  == 4 && InteractionResult1.value  == 0 && InteractionResult2.value  == 0 && InteractionResult3.value  == 0))) {
                return true;
            }

            document.getElementById('errors_ConnectionType_f').innerHTML ='';
            document.getElementById('errors_Target_f').innerHTML ='';
            document.getElementById('errors_Interaction_f').innerHTML ='';
            document.getElementById('errors_InteractionResult1').innerHTML ='';
            document.getElementById('errors_InteractionResult2').innerHTML ='';
            document.getElementById('errors_InteractionResult3').innerHTML ='';
            
            if ((Interaction_f.value > 0 && Interaction_f.value < 4) && Interaction_f.value <= 0) document.getElementById('errors_Interaction_f').innerHTML = 'الزامی است';
            if (Interaction_f.value==1 && InteractionResult1.value <= 0) document.getElementById('errors_InteractionResult1').innerHTML = 'الزامی است';
            if (Interaction_f.value==2 && InteractionResult2.value <= 0) document.getElementById('errors_InteractionResult2').innerHTML = 'الزامی است';
            if (Interaction_f.value==3 && InteractionResult3.value <= 0) document.getElementById('errors_InteractionResult3').innerHTML = 'الزامی است';

            if (ConnectionType_f.value <= 0) document.getElementById('errors_ConnectionType_f').innerHTML = 'الزامی است';
            if (Target_f.value <= 0) document.getElementById('errors_Target_f').innerHTML = 'الزامی است';

       
            return false;
    }
    function validationForm_intro() 
    {
        if (ConnectionType.value > 0 && Target.value > 0 && //Result > 0 &&
                Interaction.value > 0 && Assesment.value > 0) {
                return true;
            }

            document.getElementById('errors_ConnectionType').innerHTML ='';
            document.getElementById('errors_Target').innerHTML ='';
            document.getElementById('errors_Interaction').innerHTML ='';
            document.getElementById('errors_Assesment').innerHTML ='';

            if (ConnectionType.value <= 0) document.getElementById('errors_ConnectionType').innerHTML = 'الزامی است';
            if (Target.value <= 0) document.getElementById('errors_Target').innerHTML = 'الزامی است';
            if (Interaction.value <= 0) document.getElementById('errors_Interaction').innerHTML = 'الزامی است';
            if (Assesment.value <= 0) document.getElementById('errors_Assesment').innerHTML = 'الزامی است';

       
            return false;
    }
    function submitForm(id=1) 
    {
        if(id==2)
        {
             sbtn2.disabled=true;
            if(validationForm_first())
                frm_first.submit();
            else
                sbtn2.disabled=false;
        }
        else
        {
        sbtn.disabled=true;
        if(validationForm_intro())
            frm_intro.submit();
        else
         sbtn.disabled=false;

        }
        
    }
  </script>
@endsection
