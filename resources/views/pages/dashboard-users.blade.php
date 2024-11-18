@extends('layouts.app')

@section('content')
@if(!Auth::check())
{{ url('/browse-users') }}
@else
<div id="profile">
    <h3>{{ Auth::user()->username }}</h3>
    <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
        <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" />
        <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z" />
    </svg>
</div>
<div id="dashboard-users">
    <header>
        <h1>Admin</h1>
    </header>
    <div>
        <header>
        <p onclick="location.href='{{ url('/adminPage/users') }}'">Users</p>
<p onclick="location.href='{{ url('/adminPage/changed-users') }}'">Changed Users</p>

        </header>
        <ul>
            @foreach($users as $user)
            <li id="{{ $user->id }}">
                <span>{{ $user->username }}</span>
                <span> User Actions</span>
            </li>
            @endforeach
        </ul>
    </div>
</div>
<div id="edit-create">
    <div id="edit-profile">
        <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
            <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" />
            <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z" />
        </svg>
        <h3>{{ Auth::user()->username }}</h3>
        <span></span>
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
            <span>{{ Auth::user()->name }}</span>
        </li>
    </ul>
</div>
@endif
@endsection