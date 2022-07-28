@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 text-right">
            <div class="card">
                <div class="card-header">داشبورد</div>

                <div class="card-body text-center">
                    <img src="{{asset('favicon.ico')}}" style="max-height: 10rem;" class="img-fluid pb-3 rounded" alt="">
                   <h3>
                    سلام <b>{{auth()->user()->Name}}</b> به پنل کاخ سرخ خوش آمدید
                   </h3>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
