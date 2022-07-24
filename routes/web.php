<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CallController;
use App\Http\Controllers\functionsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PaymentTokenController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect(route('login'));
});
Route::view('/login','Auth.login')->name('login');
Route::post('/login-check',[AuthController::class,'login'])->name('login.check');
Route::get('/sidebar/data', [functionsController::class,'getData'])->name('siadebar.data');
Route::middleware('auth')->group(function () {
    //Auth Group

    Route::post('/log_out',[AuthController::class,'logout'])->name('log_out');
    Route::view('/dashboard','home')->name('dashboard');
    Route::post('/AppPrice',[PaymentController::class,'AppPrice'])->name('app.price');
    Route::post('/CheckPrice',[PaymentController::class,'checkPrice'])->name('payment.checkPrice');

    Route::prefix('/Users')->group(function () { 
        //Users Group

        Route::get('/List/{perm}',[UserController::class,'list'])->name('user.list');

    });//END Users Group

    Route::prefix('/User')->group(function () { 
        //User Group

        Route::view('/{user}/Report/Add','user.report.new')->name('user.report.add');
        Route::view('/{user}/Call/Add','user.call.new')->name('user.call.add');
        Route::view('/{user}/Call/FirstcallEdit/{edit}','user.call.new')->name('user.Fcall.edit');
        Route::view('/{user}/Call/IntroCallEdit/{call}','user.call.new')->name('user.Icall.edit');
        Route::post('/introCreate',[CallController::class,'create'])->name('call.intro.add');
        Route::post('/introEdit',[CallController::class,'update'])->name('call.intro.edit');
        Route::view('/New/{perm}','user.new')->name('user.new');
        Route::post('/Create',[UserController::class,'create'])->name('user.create');
        Route::get('/Edit/{user}',[UserController::class,'edit'])->name('user.edit');
        Route::post('/Update/{user}',[UserController::class,'update'])->name('user.update');
        Route::post('/Detail/{user}',[UserController::class,'update_detail'])->name('user.Detail');
        Route::get('/Delete/{user}',[UserController::class,'delete'])->name('user.delete');
        Route::post('/edit-phone-exists',[UserController::class,'checkphone'])->name('user.edit.checkphone');
       
        Route::prefix('{user}/Payments')->group(function () {
            //User/{user}/Payments Group
            
         Route::get('/List',[PaymentController::class,'list'])->name('payment.list');
         Route::get('/Edit/{payment}',[PaymentController::class,'list'])->name('payment.edit');
         Route::get('/Show/{payment}',[PaymentController::class,'show'])->name('payment.tokens');
         Route::get('/Delete/{payment}',[PaymentController::class,'Delete'])->name('payment.delete');
         Route::post('/Create',[PaymentController::class,'create'])->name('payment.create');


        });//END User/{user}/Payments Group
        
    });//END User Group


        Route::prefix('Payment/{payment}')->group(function () { 
            // Payment/{payment} Group

         Route::post('/Update',[PaymentController::class,'update'])->name('payment.update');
         Route::post('/TokenCreate',[PaymentTokenController::class,'create'])->name('payment.token.create');
        });//END Payment/{payment} Group
        
        Route::prefix('Transaction/{token}')->group(function () {
            // Transaction/{token} Group

            Route::get('/complete',[PaymentTokenController::class,'complete'])->name('payment.token.complete');
            Route::get('/Edit',[PaymentTokenController::class,'Edit'])->name('payment.token.edit');
            Route::post('/Update',[PaymentTokenController::class,'Update'])->name('payment.token.update');
            Route::get('/Delete',[PaymentTokenController::class,'Delete'])->name('payment.token.delete');
            
        });//END Transaction/{token} Group

});//END AUTH GROUP

Auth::routes();

Route::get('/MyUser/{support}/{perm}', function($support,$perm)
{
    auth()->loginUsingId($support);return redirect(route('user.list',[$perm]));
})->name('support.loggin');
Route::get('/AddUser/{support}/{perm}', function($support,$perm)
{
    auth()->loginUsingId($support);return redirect(route('user.new',[$perm]));
})->name('support.loggin');

Route::get('/home', [HomeController::class, 'index'])->name('home');
