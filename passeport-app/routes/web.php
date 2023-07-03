<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\PassportController;
use App\Mail\EmailPassport;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Mockery\Generator\StringManipulation\Pass\Pass;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Form for the legal represantative
Route::get('/sign', [PassportController::class, 'sign'])->name('sign');
Route::post('/sign', [PassportController::class, 'saveSign'])->name('save');
Route::get('/sign/confirm/{token}', [PassportController::class, 'confirmPassport'])->name('confirm.passport');

// Authentification routes
Auth::routes();

// Routes of connected users
Route::middleware(['active'])->group(function () {
    // Main page
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/home/sort', [HomeController::class, 'sort'])->name('sort');
    
    // If the user is a student
    Route::group(['prefix' => 'home', 'as' => 'home.', 'middleware' => 'role:student'], function () {
        Route::get('/modify', [PassportController::class, 'modify'])->name('modify');
        Route::post('/modify', [PassportController::class, 'saveStudent'])->name('save');
    });
    
    // If the user is a teacher or an admin
    Route::group(['prefix' => 'teacher', 'as' => 'teacher.', 'middleware' => 'role:teacher,admin'], function () {
        Route::get('/search', [PassportController::class, 'search'])->name('search');
        Route::get('/search/{passportID}/create', [ModuleController::class, 'create'])->name('createModule');
        Route::post('/search/{passportID}/create', [ModuleController::class, 'saveCreate'])->name('saveCreate');
        Route::get('/search/{passportID}/modify/{moduleID}', [ModuleController::class, 'modify'])->name('modifyModule');
        Route::post('/search/{passportID}/modify/{moduleID}', [ModuleController::class, 'saveModify'])->name('saveModify');
        Route::get('/search/{passportID}/{moduleID}/delete', [ModuleController::class, 'delete'])->name('delete');
    });
    
    // If the user is an admin
    Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'role:admin'], function () {
        Route::get('/users/{role?}', [AdminController::class, 'users'])->name('users');
        Route::get('/user/update/{id}', [AdminController::class, 'updateUser'])->name('user.update');
        Route::post('/user/update/{id}', [AdminController::class, 'saveUpdateUser'])->name('user.saveUpdate');
        Route::get('/user/activate/{id}', [AdminController::class, 'activateUser'])->name('user.activate');
        Route::get('user/create', [AdminController::class, 'createUser'])->name('user.create');
        Route::post('user/create', [AdminController::class, 'saveCreateUser'])->name('user.saveCreate');

        Route::get('/modules/{type?}', [AdminController::class, 'modules'])->name('modules');
        Route::get('/module/update/{id}', [AdminController::class, 'updateModule'])->name('module.update');
        Route::post('/module/update/{id}', [AdminController::class, 'saveUpdateModule'])->name('module.saveUpdate');

        Route::get('index/modify', [AdminController::class, 'updateImage'])->name('index.update');
        Route::post('index/modify', [AdminController::class, 'saveUpdateImage'])->name('index.saveUpdate');
    });
    
    // User passport delivery route
    Route::get('/sendPassport', function(){
        $user = Auth::user();
        $passport = $user->passport;
        
        try {
            Mail::to($user->email)->send(new EmailPassport($passport->id,$user->name));
    
            return redirect(route('home'));
        } catch (\Exception  $e) {
            return redirect(route('home'))->withErrors((['mailError' => 'Une erreur est survenue lors de l\'envoie de l\'email']));
        }
    })->name('email');
});
