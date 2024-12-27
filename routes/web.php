<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ORMController;
use App\Http\Controllers\CustomeGuardController;
use App\Http\Controllers\CustomServiceContanierAndProviderController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\OpenAIController;
use App\Http\Controllers\GoogleMapAIController;
use App\Http\Controllers\LinkedInController;
use App\Http\Controllers\NotificationController;


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

// Route::middleware('custome_log')->get('/dashboard', function () {
//     return view('dashboard');
// });


Route::get('/dashboard', [ChatController::class, 'userList'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// orm relationship
Route::get('/test/hasOne', [ORMController::class, 'hasOne']);
Route::get('/test/hasMany', [ORMController::class, 'hasMany']);
Route::get('/test/belongsTo', [ORMController::class, 'belongsTo']);
Route::get('/test/manyToMany', [ORMController::class, 'manyToMany']);
Route::get('/test/oneToOne_Polymorphic', [ORMController::class, 'oneToOne_Polymorphic']);
Route::get('/test/oneToMany_Polymorphic', [ORMController::class, 'oneToMany_Polymorphic']);
Route::get('/test/manyToMany_Polymorphic', [ORMController::class, 'manyToMany_Polymorphic']);

//custome guard and middleware
Route::get('/admin/custome/guard/login', [CustomeGuardController::class, 'loginPage'])->name('admin.login');
Route::post('/admin/custome/guard/login', [CustomeGuardController::class, 'login']);

Route::middleware('admin')->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/custome/guard/user', [CustomeGuardController::class, 'user']);
});

//service contanier and provider and facade
Route::middleware('auth')->group(function () {
    Route::get('/custome/service/contanier', [CustomServiceContanierAndProviderController::class, 'serviceContanier']);
    Route::get('/send-notification', [CustomServiceContanierAndProviderController::class, 'sendNotification']);
    // facade
    Route::get('/facade', [CustomServiceContanierAndProviderController::class, 'createUser']);
});


//Socialite
Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('google.redirect');
Route::get('/auth/google/call-back', [GoogleAuthController::class, 'callbackGoogle'])->name('google.callback');

//event
Route::get('/order/ship', [OrderController::class, 'oderShip'])->name('order.ship');

//web socket
Route::post('/webSocket/msg', [ChatController::class, 'msg'])->name('webSocket.msg');

//dynamic chat
Route::middleware('auth')->group(function () {
    Route::get('/user/list', [ChatController::class, 'userList']);
    Route::get('/old/msg', [ChatController::class, 'oldMsg']);
});

//open ai api
Route::get('/Job/dissatisfaction', [OpenAIController::class, 'jobDissatisfaction'])->name('job.dissatisfaction');
Route::post('/generate/text', [OpenAIController::class, 'generateText']);
Route::post('/submit/answers', [OpenAIController::class, 'submitAnswers']);

//google map api
Route::get('/map', [GoogleMapAIController::class, 'map'])->name('map');

//linkdin api
Route::get('/linkedin/auth', [LinkedInController::class, 'redirect'])->name('linkedin.auth');
Route::get('/linkedin/callback', [LinkedInController::class, 'handleCallback'])->name('linkedin.callback');
Route::post('/linkedin/post', [LinkedInController::class, 'postToLinkedIn'])->name('linkedin.post');

//notification
Route::get('/notification', [NotificationController::class, 'notificationList'])->name('notification');
Route::get('/order/notification', [NotificationController::class, 'oderShip'])->name('order.notification');


require __DIR__.'/auth.php';
