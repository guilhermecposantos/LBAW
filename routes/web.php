<?php

use Illuminate\Support\Facades\Route;


use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;
use App\Models\Topic;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\FAQController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EventController;
use App\Exceptions\Handler;

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
    return redirect('/browse');
});

Route::get('/browse', function () {
    $events = Event::allEvents()->get();
    $user = Auth::user();
    $topics = Topic::topics();
    return view('pages.browse', ['events' => $events, 'user' => $user, 'topics' => $topics]);
    
});

Route::post('/ajax-request', [EventController::class, 'handleAjax']);
Route::post('/update-comment', [EventController::class,'updateComment'])->name('updateComment');
Route::post('/submit-poll-vote', [EventController::class,'updateVotes']);

// Home 
Route::middleware(['web', 'auth'])->group(function () {
    
    Route::get('dashboard/future/attendee', [EventController::class, 'events']);
    Route::get('dashboard/past/attendee', [EventController::class, 'pastEvents']);
    Route::get('dashboard/future/organizer', [EventController::class, 'userEvents']);
    Route::get('dashboard/past/organizer', [EventController::class, 'eventsOrganized']);
    Route::get('event/{id}', [EventController::class, 'eventDetails']);
    Route::get('user/{id}/ban', [UserController::class, 'userBan']);
    Route::get('user/{id}/unban', [UserController::class, 'userUnBan']);
    Route::get('user/{id}/delete', [UserController::class, 'userDelete']);
    Route::get('user/{id}', [UserController::class, 'userDetails']);
    Route::post('event/{id}/comment', [EventController::class, 'addComment'])->name('event.addComment');
    Route::post('event/{id}/poll-comment', [EventController::class, 'addPoll'])->name('pollComment');
    Route::get('create-event', [EventController::class, 'showCreateEventsForm']);
    Route::post('create-event', [EventController::class, 'create'])->name('create');
    Route::get('event/{id}/edit', [EventController::class, 'showEditEventsForm']);
    Route::post('event/{id}/edit', [EventController::class, 'edit']);
    Route::post('event/{id}/delete', [EventController::class, 'delete']);
    Route::post('event/{event_id}/delete-comment/{id}', [EventController::class,'deleteComment']);
    Route::post('event/{id}/addAttendee', [EventController::class, 'addAttendee']);
    Route::post('event/{id}/closeEvent', [EventController::class, 'closeEvent']);
    Route::post('/event/{id}/requestJoin', [EventController::class, 'requestJoin']);
    Route::get('/adminPage/users', [UserController::class, 'users']);
    Route::get('/adminPage/changed-users', [UserController::class, 'changedUsers']);
    Route::post('event/{id}/leaveAttendee', [EventController::class, 'leaveAttendee']);
    Route::get('/adminPage', [UserController::class, 'users']);
    Route::get('dashboard-users/view_users', [UserController::class, 'users']);
    Route::post('/accept-request/{event_id}/{user_id}', [EventController::class, 'acceptJoinRequest'])->name('accept-request');
    Route::post('/reject-request/{event_id}/{user_id}', [EventController::class, 'rejectJoinRequest'])->name('reject-request');
});
// Route::redirect("/", "/qualquercoisa");

// Authentication
Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'authenticate');
    Route::get('/logout', 'logout')->name('logout');
});

//User
Route::controller(UserController::class)->group(function () {
    Route::get('/editprofile', 'showEditProfilePage')->name('editprofile');
    Route::post('/editprofile', 'editProfile');
});

Route::controller(UserController::class)->group(function () {
    Route::get('/profile', 'showProfilePage')->name('profile');
    Route::post('/profile', 'editProfile');
});

Route::controller(UserController::class)->group(function () {
    Route::get('/deleteprofile', 'deleteprofile');
});

Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'showRegistrationForm')->name('register');
    Route::post('/register', 'register');
});


Route::fallback([DashboardController::class, 'redirectAfterError']);

Route::get('/about', [AboutController::class, 'showAboutPage'])->name('about');
Route::get('/faq', [FAQController::class, 'showHelpPage'])->name('faq');