@extends('layouts.app')

@section('content')
@if(Auth::check())
<div id="profile">
    <h3>{{ Auth::user()->username }}</h3>
    <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-person-circle"
        viewBox="0 0 16 16">
        <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" />
        <path fill-rule="evenodd"
            d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z" />
    </svg>
</div>
@else
<div id="authenticate">
    <a href="{{ url('/login') }}">Login</a>
    <a href="{{ url('/register') }}">Register</a>
</div>
@endif
<div id="user-details">
    <div id="back">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left"
                viewBox="0 0 16 16">
                <path fill-rule="evenodd"
                    d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8" />
            </svg>
            <span>
                <a href="{{ url()->previous() }}">
                    Go back
                </a>
            </span>
    </div>
    <h1>User Details</h1>
    <header>
        <h2>{{ $user->username }}</h2>
        <span>&#64;{{ $user->username }}</span>
    </header>
    <p>{{ $user->email }}</p>
        
    <span>{{ date_format(date_create($user->created_at), "d/m/Y H:i") }}</span>
    <span>{{ $user->isadmin ? 'Admin' : 'User'}}</span>
<div id= "user-controls">
    <div id="delete-user">
        <a href="{{ url('user/'.$user->id.'/delete') }}">Delete User</a>
    </div>
    <div id="ban-user">
        <a href="{{ url('user/'.$user->id.'/ban') }}">Ban User</a>
    </div>
    <div id="unban-user">
        <a href="{{ url('user/'.$user->id.'/unban') }}">Unban User</a>
    </div>
</div>
<div id="dropdown">
        <ul>
            <li>
                <a href="{{ url('/dashboard/future/attendee') }}">
                    Dashboard
                </a>
    
            </li>

            @if (Auth::user()->isadmin)
            <li>
                <a href="{{ url('/adminPage/users') }}">
                    Admin
                </a>
            </li>
            @endif

            <li><a href="{{ url('/faq') }}">Help</a></li>
            <li><a href="{{ url('/about') }}">About</a></li>
            <li>
                <a href="{{ url('/logout') }}"> Logout </a>
                @if(Auth::check())<span>{{ Auth::user()->name }}</span>
                @else
                @endif
            </li>
        </ul>
    </div>

    @endsection
