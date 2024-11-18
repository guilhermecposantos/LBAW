<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;

class UserController extends Controller
{
    public function users()
    {
        $users = User::allUsers()->get();
        return view('pages.dashboard-users', ['users' => $users]);
    }
    public function changedUsers(){
        $users = User::allUsersChanged()->get();
        return view('pages.dashboard-users', ['users' => $users]);
    }

    public function showProfilePage()
    {

        return view("pages.profile", [
            'user' => Auth::user(),
            'old' => [
                'firstname' => Auth::user()->firstname,
                'lastname' => Auth::user()->lastname,
                'username' => Auth::user()->username,
                'email' => Auth::user()->email
            ]
        ]);
    }
    public function showEditProfilePage()
    {

        return view("pages.edit_profile", [
            'user' => Auth::user(),
            'old' => [
                'firstname' => Auth::user()->firstname,
                'lastname' => Auth::user()->lastname,
                'username' => Auth::user()->username,
                'email' => Auth::user()->email
            ]
        ]);
    }

    public function editProfile(Request $request)
    {

        $this->authorize('edit', User::class);
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'username' => 'unique:users,username,' . $user->id . '|max:255',
            'email' => 'email|unique:users,email,' . $user->id . '|max:255',
        ]);

        $user->firstname = $request['first-name'];
        $user->lastname = $request['last-name'];
        $user->username = $request['username'];
        $user->email = $request['email'];

        $user->save();

        return redirect()->intended('/dashboard/future/attendee');
    }

    public function userDetails($id){
        $user = User::find($id);
        return view('pages.user_details', ['user' => $user]);
    }

    public function userBan($id)
    {
        $user = User::find($id);
        if($user->isadmin == 1){
            return redirect()->intended('/adminPage/users');
        }
        $user->isbanned = true;
        $user->save();
        return redirect()->intended('/adminPage/users');
    }

    public function userUnBan($id)
    {
        $user = User::find($id);
        if($user->isadmin == 1){
            return redirect()->intended('/adminPage/users');
        }
        $user->isbanned = false;
        $user->save();
        return redirect()->intended('/adminPage/users');
    }

    public function userDelete($id)
    {
        $user = User::find($id);
        if($user->isadmin == 1){
            return redirect()->intended('/adminPage/users');
        }
        $user->isdeleted = true;
        $user->save();
        return redirect()->intended('/adminPage/users');
    }
    

    public function deleteprofile()
    {

        $user = Auth::user();

        $user->isdeleted = true;

        $user->save();

        Auth::logout();

        return redirect()->intended('/login');
    }
}
