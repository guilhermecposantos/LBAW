@extends('layouts.app')

@section('content')
@if(Auth::check())
<div id="profile">
    <h3>{{ Auth::user()->username }}</h3>
    <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
        <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" />
        <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z" />
    </svg>
</div>
@else
<div id="authenticate">
    <a href="{{ url('/login') }}">Login</a>
    <a href="{{ url('/register') }}">Register</a>
</div>
@endif
    <form method="GET" action=>
        <input type="text" name="search" id="searchInput">
    </form>
    <div id="browse-users">
        <header>
            <h1>Browse Users</h1>
        </header>
        <div>
            <ul>
                @foreach($users as $user)
                <li id="{{ $user->id }}">

                    <span>{{ $user->username }}</span>
                    <span>{{$user->id}}</span>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
<div id="filters">
    <header>
        <h1>Filters</h1>
    </header>
    
    <button id="save-filters">Save Filters</button>
</div>
<div id="dropdown">
    <ul>
        <li>
            <a href="{{ url('/browse') }}">
                Dashboard-Users
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
            <span>{{ Auth::user()->name }}</span>
        </li>
    </ul>
</div>

@endsection