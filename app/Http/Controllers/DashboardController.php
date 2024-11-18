<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Topic;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

use Illuminate\View\View;


class DashboardController extends Controller
{

    public function redirectToHome()
    {
        $events = Event::allEvents()->get();
        $topics = Topic::topics();
        return view("pages.browse", ['events' => $events], ['user' => Auth::user()], ['topics' => $topics]);
    }

    public function redirectAfterError(){
        
        return redirect('/browse');
    }

    
    public function default()
    {
        $user = Auth::user();
        if($user->username =='deleted'){
            Auth::logout();
            return redirect('/login');
        }
        return view("pages.profile", compact("user"));
    }
}