<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\AboutController;
//use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
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
    return view('welcome');
});

/* Route::get('/about', function () {
    return view('about');
}); */

Route::get('/about', [AboutController::class, 'index']);
Route::get('/login', [UserController::class, 'login'])->name('login');
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth','adminAccess');
Route::get('/user/create', [UserController::class, 'create']);
Route::post('/signup', [UserController::class, 'store'])->name('signup');


Route::get('/logout', function () { 
    Auth::logout();
    return redirect('login');
})->name('logout');

Route::post('/signin', function(Request $request){
    
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->intended('dashboard');
    }

    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ]);

});
