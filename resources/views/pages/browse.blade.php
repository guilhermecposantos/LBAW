@extends('layouts.app')

@section('content')
@if(Auth::check())
<meta name="csrf-token" content="{{ csrf_token() }}">
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
    <div id="browse">
        <header>
            <h1>Browse Events</h1>
        </header>
        <form method="GET" id="search">
            <input type="text" name="search" id="searchInput" autocomplete='off' spellcheck='false'>
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
            </svg>
        </form>
        <div id="events">
            <ul >
            @foreach($events as $event)
            <li id="{{ $event->id }}" >
                <span>{{ $event->title }}</span>
                @if($event->status == "ongoing")
                <span>{{ $event->status }}</span>
                @else
                <span>{{ date_format(date_create($event->eventdatetime), "d/m/Y H:i") }}</span>
                @endif
            </li>
            @endforeach
            </ul>
        </div>
    </div>

    
    
    <div id="filters">
        <select id="topic" name="topic">
            @foreach ($topics as $topic)
                <option value="{{ $topic->id }}">{{ $topic->title }}</option>
            @endforeach
        </select>
        <button id="save-filters">Save Filters</button>
    </div>
    <div id="dropdown">
        <ul>
            <li>
                <a href="{{ url('/dashboard/future/attendee') }}">
                    Dashboard
                </a>
    
            </li>
            @if(Auth::check())
            @if (Auth::user()->isadmin)
            <li>
                <a href="{{ url('/adminPage/users') }}">
                    Admin
                </a>
            </li>
            @else
            @endif
            @else
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