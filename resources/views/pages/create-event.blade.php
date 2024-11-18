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
<form method="POST" action="{{ route('create') }}" class="auth" enctype="multipart/form-data">
    {{ csrf_field() }}
    <h1>Create Event</h1>
    <section id="create-event-form">
        <div>
            <label for="title">Title</label>
            <input id='title' type="text" name="title">
        </div>

        <div>
            <label for="description">Description</label>
            <textarea id="description" name="description"></textarea>
        </div>

        <div>
            <label for="location">Location</label>
            <input id="location" type="text" name="location">
        </div>

        <div>
            <label for="url">Image Upload</label>
            <input id="url" type="file" name="url" accept="image/*">
        </div>

        <div>
            <label for="topic">Topic</label>
            <select id="topic" name="topic">
                @foreach ($topics as $topic)
                <option value="{{ $topic->id }}">{{ $topic->title }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="eventdatetime">Event Date</label>
            <input id="eventdatetime" type="datetime-local" name="eventdatetime">
        </div>

        <div>
            <label for="ispublic">Is Public?</label>
            <select id="ispublic" name="ispublic">
                <option value="true">Yes</option>
                <option value="false">No</option>
            </select>
        </div>

        <div>
            <label for="totalTickets">Total Tickets</label>
            <input id="totalTickets" type="number" name="totalTickets" min="0">
        </div>

       
    </section>
    <div>
        <label for="ticketPrice">Ticket Price</label>
        <input id="ticketPrice" type="number" name="ticketPrice" min="0">
    </div>
    <button type="submit">
        Create
    </button>
</form>