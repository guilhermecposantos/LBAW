<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Event;
use App\Models\JoinRequest;
use App\Models\Topic;
use App\Models\PollOption;
use App\Models\PollVote;
use App\Models\User;
use App\Models\Attendee;
use App\Models\Comment;
use App\Models\Ticket;


class EventController extends Controller
{
  public function events()
  {
      $user = Auth::user();
      $events = Event::allUserGoingEvents($user)->get();
      $organizedEvents = Event::where('organizerid', $user->id)->get();
      $eventRequests = [];
  
      foreach ($organizedEvents as $event2) {
          $requests = JoinRequest::where('event_id', $event2->id)->get();
          $requestsData = [];
  
          foreach ($requests as $request) {
              $user2 = User::find($request->user_id);
              $requestsData[] = [
                  'user' => $user2,
                  'request' => $request
              ];
          }
  
          $eventRequests[] = [
              'event' => $event2,
              'requests' => $requestsData
          ];
      }
  
      return view('pages.dashboard', [
          'events' => $events,
          'eventRequests' => $eventRequests
      ]);
  }
  
    
  

  public function eventsBrowse()
  {
    $events = Event::allEvents()->get();
    return view('pages.browse', ['events' => $events]);
  }

  public function userEvents()
  {
    /** @var \App\Models\User $user */
    
    
  
    $user = Auth::user();
    $events = Event::getEvents($user)->get();
    $organizedEvents = Event::where('organizerid', $user->id)->get();
    $eventRequests = [];

    foreach ($organizedEvents as $event2) {
        $requests = JoinRequest::where('event_id', $event2->id)->get();
        $requestsData = [];

        foreach ($requests as $request) {
            $user2 = User::find($request->user_id);
            $requestsData[] = [
                'user' => $user2,
                'request' => $request
            ];
        }

        $eventRequests[] = [
            'event' => $event2,
            'requests' => $requestsData
        ];
    }

    return view('pages.dashboard', [
        'events' => $events,
        'eventRequests' => $eventRequests
    ]);
}
    


  public function pastEvents()
  {
    $user = Auth::user();
    $events = Event::getPastEvents($user)->get();
    $organizedEvents = Event::where('organizerid', $user->id)->get();
    $eventRequests = [];

    foreach ($organizedEvents as $event2) {
        $requests = JoinRequest::where('event_id', $event2->id)->get();
        $requestsData = [];

        foreach ($requests as $request) {
            $user2 = User::find($request->user_id);
            $requestsData[] = [
                'user' => $user2,
                'request' => $request
            ];
        }

        $eventRequests[] = [
            'event' => $event2,
            'requests' => $requestsData
        ];
    }

    return view('pages.dashboard', [
        'events' => $events,
        'eventRequests' => $eventRequests
    ]);
  }

  public function eventsFromDeletedOrganizers()
  {
    $events = Event::getEventsFromDeletedOrganizers()->get();
    return view('pages.dashboard', ['events' => $events]);
  }

  public function eventsOrganized()
  {
    /** @var \App\Models\User $user */
    
    $user = Auth::user();
    $events = Event::getEventsOrganized($user)->get();
    $organizedEvents = Event::where('organizerid', $user->id)->get();
    $eventRequests = [];

    foreach ($organizedEvents as $event2) {
        $requests = JoinRequest::where('event_id', $event2->id)->get();
        $requestsData = [];

        foreach ($requests as $request) {
            $user2 = User::find($request->user_id);
            $requestsData[] = [
                'user' => $user2,
                'request' => $request
            ];
        }

        $eventRequests[] = [
            'event' => $event2,
            'requests' => $requestsData
        ];
    }

    return view('pages.dashboard', [
        'events' => $events,
        'eventRequests' => $eventRequests
    ]);
  }

  public function eventDetails($id)
{
  $event = Event::find($id);
  $topic = Topic::find($event->topicid);
  $user = User::find($event->organizerid);
  $comments = $event->comments()->get();
  $commentsComplete = [];
  foreach ($comments as $comment){
    $commentUser = User::find($comment->user_id);
    if($comment->ispoll) {
      $commentsComplete[] = [
        'comment' => $comment,
        'username' => $commentUser,
        'options' => $comment->pollOptions
      ]; 
    }
    else{$commentsComplete[] = [
        'comment' => $comment,
        'username' => $commentUser,
        'options' => null
      ]; 
    }    
    
  }
  $attendees = $event->attendees()->get();
  $attendeesComplete = [];
  foreach ($attendees as $attendee) {
    $temp = User::find($attendee->user_id);
    $attendeesComplete[] = $temp;
  }
  $event->availabletickets = $event->totaltickets - Attendee::where('event_id', $event->id)->count();
  return view('pages.event_details', ['event' => $event, 'user' => $user, 'attendeesComplete' => $attendeesComplete, 'topic' => $topic, 'commentsComplete' => $commentsComplete]);
  
}

public function addComment(Request $request, $id)
{     
  Log::info('Testing log');
  
    $request->validate([
        'comment' => 'required|max:2000', 
    ]);

    Comment::insert([
        'user_id' => Auth::user()->id,
        'event_id' => $id,
        'content' => $request->comment
    ]);

    
    return redirect('event/' . $id);
}


  public function showCreateEventsForm(){
    $user = Auth::user();
    $topics = Topic::topics();
    return view('pages.create-event', ['user' => $user, 'topics' => $topics]);
  }


  public function showEditEventsForm($id){
    $event = Event::find($id);
    $user = User::find($event->organizerid);
    return view("pages.edit-event", [
      'user' => $user,
      'event' => $event,
      'old' => [
          'title' => $event->title,
          'description' => $event->description,
          'location' => $event->location
      ]
  ]);
  }
  public function create(Request $request)
  {

    if(Auth::user()->isbanned == 1){
      return redirect()->intended('/dashboard/future/attendee');
    }
    $event = new Event();
    $event->title = $request->title;
    $event->description = $request->description;
    $event->eventdatetime = $request->eventdatetime;
    $event->location = $request->location;
    
    if ($request->hasFile('url')) {
      $path = $request->file('url')->store('events', 'public');
      $event->url = $path;
  }
    
    $event->topicid = $request->topic;
    $event->organizerid = Auth::user()->id;
    $event->ispublic = $request->ispublic;
    $event->totaltickets = $request->input('totalTickets');
    $event->availabletickets = $request->input('totalTickets');
    $event->ticketprice = $request->input('ticketPrice');
    $event->created_at = now();
    $event->updated_at = null;
    $event->save();

    return redirect()->intended('/dashboard/future/organizer');
  }

      public function updateComment(Request $request)
    {
        $request->validate([
            'commentId' => 'required|integer',
            'content' => 'required|string|max:2000',
        ]);

        $commentId = $request->input('commentId');
        $content = $request->input('content');

        $comment = Comment::find($commentId);
        if ($comment && $comment->user_id == Auth::id()) {
            $comment->content = $content;
            $comment->save();

            return response()->json([
                'success' => true,
                'updatedContent' => $comment->content
            ]);
        }

        return response()->json(['success' => false], 403); 
    }

    public function deleteComment($event_id ,$id){
      $event = Event::find($event_id);
      DB::table('comment')->where('id', $id)->delete();
      
      return redirect()->intended('event/'.$event->id);
    }

    public function addPoll(Request $request, $id){
      $comment = new Comment;
        $comment->user_id = auth()->id();
        $comment->event_id = $id; 
        $comment->content = $request->input('comment');
        $comment->ispoll = true;
        $comment->save();

    foreach ($request->input('options') as $option) {
      if (trim($option) != '') {
        PollOption::insert([
          'comment_id' =>  $comment->id,
          'option' => $option
        ]);
      }
  }

  return redirect('event/' . $id);
}
  
  public function handleAjax(Request $request){

    $selectedTopic = $request->input('selectedTopic');
      
    $events = Event::where('topicid', $selectedTopic)->get();

    return response()->json(['events' => $events]);
  }


  public function delete($id)
  {
    $event = Event::find($id);
    $event->delete();
    return redirect()->intended('/dashboard/future/organizer');
  }

  public function edit(Request $request)
  {
    $event = Event::find($request->id);

    $event->title = $request['title'];
    $event->description = $request['description'];
    $event->location = $request['location'];
    $event->save();

    return redirect()->intended('/dashboard/future/organizer');
  }

  public function closeEvent($id){
    $event = Event::find($id);
    $event->status = 'closed';
    $event->save();

    return redirect()->intended('/dashboard/past/organizer');
  }


  public function search(Request $request)
  {
    $searchTerm = $request->input('search');
    $events = Event::query()
      ->where('title', 'LIKE', "%{$searchTerm}%")
      ->orWhere('description', 'LIKE', "%{$searchTerm}%")
      ->get();

    return response()->json($events);
  }

  public function addAttendee($id) {
        
    $event = Event::find($id);
  
    Attendee::insert([
        'user_id' => Auth::user()->id,
        'event_id' => $event->id
    ]);


      $event->availabletickets = $event->totaltickets - Attendee::where('event_id', $event->id)->count();
      $event->save();

      Ticket::create([
          'eventid' => $event->id,
          'userid' => Auth::user()->id,
          'price' => $event->ticketprice,
          'paymentmethod' => 'PayPal',
      ]);
  

    return redirect()->intended('event/'.$event->id);
  }

  public function requestJoin(Request $request, $id)
{
    $userId = Auth::id();
    $event = Event::find($id);

    $existingRequest = JoinRequest::where('user_id', $userId)->where('event_id', $id)->first();
    if ($existingRequest || $event->attendees->contains($userId)) {
        return back()->with('error', 'You have already requested to join or are an attendee.');
    }

    JoinRequest::insert([
        'user_id' => $userId,
        'event_id' => $event->id,
    ]);

    return redirect()->intended('event/'.$event->id);
  }
  
  public function leaveAttendee($id) {
    

    $userId = Auth::id();
    $user = User::find($userId);
    DB::table('attends')->where('user_id', $userId)->where('event_id', $id)->delete();
    
    $event = Event::find($id);
    $event->availabletickets++;
    $event->save();
    $ticket = Ticket::ticketsFromUserAndEvent($user, $event)->first();
    $ticket->delete();

    return redirect()->intended('event/'.$id);
  }

  public function updateVotes(Request $request)
    {
      $optionId = $request->input('optionId');
      $pollId = $request->input('pollId');
      $userId = Auth::id();

      $existingVote = PollVote::whereHas('pollOption', function ($query) use ($pollId) {
        $query->where('comment_id', $pollId);
          })->where('user_id', $userId)->first();

      if($existingVote){
        if ($existingVote->option_id != $optionId) {
          $existingVote->option_id = $optionId;
          $existingVote->save();
        }
      } 
      else {
        $vote = new PollVote();
        $vote->option_id = $optionId;
        $vote->user_id = $userId;
        $vote->save();
      }     

      $options = PollOption::where('comment_id', $pollId)->withCount('votes')->get();
      $voteCounts = $options->map(function ($option) {
        return [
            'optionId' => $option->id,
            'count' => $option->votes_count
        ];
    });
    
      return response()->json(['success' => true, 'message' => 'Vote submitted successfully', 'voteCounts' => $voteCounts]);

    }

    public function acceptJoinRequest($event_id, $user_id)
{
  DB::table('join_request')->where('user_id', $user_id)->where('event_id', $event_id)->delete();

    Attendee::insert([
        'user_id' => $user_id,
        'event_id' => $event_id
    ]);
    
    return back();
}

public function rejectJoinRequest($event_id, $user_id)
{
  DB::table('join_request')->where('user_id', $user_id)->where('event_id', $event_id)->delete();

    return back();
}

  
}