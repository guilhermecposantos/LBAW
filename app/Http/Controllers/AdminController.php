<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

use Illuminate\View\View;


class AdminController extends Controller
{

    public function redirectToHome()
    {
        $events = Event::allEvents()->get();
        return view("pages.browser", ['events' => $events]);
    }

    public function redirectAfterError()
    {

        return redirect('/adminPage/users');
    }


    public function default()
    {
        $user = Auth::user();

        return view("pages.adminProfile", compact("user"));
    }

    public function deleteUser($id)
    {
        $user = User::find($id);
        $user->isdeleted = 1;
        $user->save();
        return redirect('/adminPage/users');
    }
}
