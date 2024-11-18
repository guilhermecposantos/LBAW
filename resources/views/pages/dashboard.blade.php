@extends('layouts.app')

@section('content')
@if(!Auth::check())
{{ url('/browse') }}
@else
<div id="profile">
    <h3>{{ Auth::user()->username }}</h3>
    <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
        <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" />
        <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z" />
    </svg>
</div>
<div id="dashboard">
    <header>
        <h1>User Dashboard</h1>
        <div id="switch">
            <span>Attendee</span>
            <label class="toggle-control">
                <input type="checkbox">
                <span class="control"></span>
            </label>
            <span>Organizer</span>
        </div>
    </header>
    <div>
        <header>
            <p>Future Events</p>
            <p>Past Events</p>
        </header>
        <ul>
            @foreach($events as $event)
            <li id="{{ $event->id }}">
                <span>{{ $event->title }}</span>
                <span>{{ date_format(date_create($event->eventdatetime), "d/m/Y H:i") }}</span>
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
        <a href="{{ url('/editprofile') }}">Edit Profile</a>
        <a href="{{ url('/deleteprofile') }}">Delete Profile</a>
    </div>
    <div id="create-event">
        <a href="{{ url('/create-event') }}">Create Event</a>
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
<div id="attendees" class= "join">
        <header>
            <h1>Join Requests</h1>
        </header>
        <ul>
            @foreach ($eventRequests as $eventRequest)
                <li>{{ $eventRequest['event']->title }} - Requests:</li>
                <ul>
                    @foreach ($eventRequest['requests'] as $request)
                        <li>{{ $request['user']->username }} requested to join.
                            <form method="POST" action="{{ route('accept-request', ['event_id' => $eventRequest['event']->id, 'user_id' => $request['user']->id]) }}">
                                @csrf
                                <button type="submit">Accept</button>
                            </form>
                            <form method="POST" action="{{ route('accept-request', ['event_id' => $eventRequest['event']->id, 'user_id' => $request['user']->id]) }}">
                                @csrf
                                <button type="submit">Reject</button>
                            </form>
                        </li>
                    @endforeach
                </ul>
            @endforeach
        </ul>
    </div>

@endif
@endsection