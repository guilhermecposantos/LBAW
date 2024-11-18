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
<div id="event-details">
    <div id="back">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left"
                viewBox="0 0 16 16">
                <path fill-rule="evenodd"
                    d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8" />
            </svg>
            <span>
                @if(Auth::check())
                <a href="{{ url('/browse') }}">
                    Go back
                </a>
                @endif
            </span>
    </div>
    <h1>Event Details</h1>
    <header>
        <h2>{{ $event->title }}</h2>
        <span>&#64;{{ $user->username }}</span>
    </header>
    <p>{{ $event->description }}</p>
    <p>{{ $topic->title}}</p>
    <span>{{ date_format(date_create($event->eventdatetime), "d/m/Y H:i") }}</span>
    <span>{{ $event->ispublic ? 'Public' : 'Private'}}</span>
    <span>{{ number_format($event->availabletickets) }} tickets available of {{ number_format($event->totaltickets)}}</span>
    <span>{{ $event->ticketprice }}</span>

    @if(Auth::user()->id == $event->organizerid)
    <div id="edit-event">
        <a href="{{ url('event/'.$event->id.'/edit') }}">Edit Event</a>
    </div>
    @endif

    @if (File::exists(public_path('storage/' . $event->url)))
    <img src="{{ asset('storage/' . $event->url) }}" alt="Event Image" id="event-image">
    @else
    <img src="/img/image_not_found.png" alt="Image Not Found" id="event-image">
    @endif
</div>

@if($event->status != "closed")
<div id="attendees-comments">
    <div id="attendees">
        <header>
            <h1>Attendees</h1>
        </header>
        <ul>
            @foreach ($attendeesComplete as $attendee)
            <li id="{{ $attendee->id }}">
                {{ $attendee->username }}
            </li>
            @endforeach
        </ul>
        @php
        $isCurrentUserAttending = false;
        $currentUserId = Auth::id();
        foreach ($attendeesComplete as $attendee) {
            if ($attendee->id == $currentUserId) {
                $isCurrentUserAttending = true;
                break;
            }
        }
        @endphp
        @if(Auth::user()->id != $event->organizerid)
            @if ($isCurrentUserAttending)
                <form method="POST" action="{{ url('event/'.$event->id.'/leaveAttendee') }}">
                @csrf
                <button type="submit">Leave</button>
                </form>
            @else
                @if($event->ispublic)
                    <form method="POST" action="{{ url('event/'.$event->id.'/addAttendee') }}">
                    @csrf
                    <button type="submit">
                    I want to go!
                    </button>
                    </form>
                @else
                    <form method="POST" action="{{ url('event/'.$event->id.'/requestJoin') }}">
                    @csrf
                    <button type="submit">Request to Join</button>
                    </form>
                @endif
            @endif
        @else
        <form method="POST" action="{{ url('event/'.$event->id.'/closeEvent') }}">
            @csrf
            <button type="submit">
            Close Event
            </button>
            </form>
        @endif
    </div>
    
    <div id="comments">
        <header>
            <h1>Comments</h1>
        </header>
        <ul>
            @foreach ($commentsComplete as $comment)
                <li id="comment-li-{{ $comment['comment']->id }}" class='comment'>
                    @if ($comment['comment']->ispoll)
                    <div class="poll">
                        <span>{{ $comment['username']->username }}</span>
                        <p class="comment-content" 
                         @if(Auth::user()->id == $comment['comment']->user_id)
                        data-comment-id="{{ $comment['comment']->id }}"
                        @endif>
                        {{ $comment['comment']->content }}
                        </p>
                        @foreach ($comment['options'] as $option)
                            <label>
                                <input type="radio" name="poll_{{ $comment['comment']->id }}" value="{{ $option->id}}">
                                {{ $option->option}} (<a id="vote-count-{{ $option->id }}">{{ $option->votes->count() }}</a> votes)
                            </label>
                        @endforeach
                    </div>
                    @else
                    <span>{{ $comment['username']->username }}</span>
                    <p class="comment-content" 
                    @if(Auth::user()->id == $comment['comment']->user_id)
                    data-comment-id="{{ $comment['comment']->id }}"
                    @endif>
                        {{ $comment['comment']->content }}
                    </p>
                    @if(Auth::user()->id == $comment['comment']->user_id)
                    <form method="POST" action="{{ url('event/'.$event->id.'/delete-comment/'.$comment['comment']->id) }}">
                    <span>{{ $comment['comment']->created_at }}</span>
                        @csrf
                        <button type="submit">
                            Delete
                        </button>
                    </form>
                    
                    @endif
                    @endif
                </li>
            @endforeach
        </ul>
        <div id="comment-toggle">
            <button type="button" id="normal-comment-btn">Normal Comment</button>
            <button type="button" id="poll-comment-btn">Poll Comment</button>
        </div>
        
        <div id="normal-comment-form">
            <form method="POST" action="{{ route('event.addComment', ['id' => $event->id]) }}">
                @csrf
                <textarea name="comment" id="comment" cols="50" rows="3" placeholder="Write a comment..." maxlength=200></textarea>
                <button type="submit">Comment</button>
            </form>
        </div>
        
        <div id="poll-comment-form" style="display: none;">
            <form method="POST" action="{{ route('pollComment', ['id' => $event->id]) }}">
                @csrf
                <textarea name="comment" id="poll-comment" cols="50" rows="3" placeholder="Write a poll question..." maxlength=200></textarea>
                <label>Poll Options</label>
                <div id="poll-options">
                    <input type="text" name="options[]" placeholder="Option 1">
                    <input type="text" name="options[]" placeholder="Option 2">
                </div>
                <div style = "display: flex; flex-direction: row;">
                <button type="button" id="add-option">Add Option</button>
                <button type="submit">Post Poll</button>
                </div>
            </form>
        </div>
        
        
    </div>
</div>
@else
<div id="attendees-comments">
    EVENT CLOSED!
</div>
@endif

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

<section id="expanded-image">
    @if (File::exists(public_path('storage/' . $event->url)))
    <img src="{{ asset('storage/' . $event->url) }}" alt="Event Image" id="event-image">
    @else
    <img src="/img/image_not_found.png" alt="Image Not Found" id="event-image">
    @endif
    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-x-circle" viewBox="0 0 16 16">
        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
        <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
    </svg>
</section>

@endsection


