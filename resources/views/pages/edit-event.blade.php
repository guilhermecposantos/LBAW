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
<form method="POST" action="{{ url('event/'.$event->id.'/edit') }}" enctype="multipart/form-data" id='edit-profile-form'>
    @csrf
    <h1>Edit event</h1>
    <label for="title">Title</label>
    <input id='title' type="title" name="title" 
    value="{{ old('title', $old['title']) }}" required autofocus spellcheck='false' autocomplete='off'> 
    <label for="description">Description</label>
    <input id="description" type="description" name="description"
    value="{{ old('description', $old['description']) }}" required autofocus spellcheck='false' autocomplete='off'>
    <label for="location">Location</label>
    <input id="location" type="location" name="location"
    value="{{ old('location', $old['location']) }}" required autofocus spellcheck='false' autocomplete='off'>
    <button type="submit">
        Save changes
    </button>

</form>

<form method="POST" action="{{ url('event/'.$event->id.'/delete') }}">
    @csrf
    <button type="submit">
        Delete Event
    </button>
</form>



