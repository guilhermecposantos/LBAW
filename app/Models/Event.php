<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Post;
use App\Models\Topic;


class Event extends Model
{
    use HasFactory;

    protected $table = 'event';

    protected $fillable = [
        'organizerid',
        'title',
        'description',
        'eventdatetime',
        'url',
        'location',
        'topicid',
        'startsales',
        'endsales',
        'ispublic',
        'availabletickets',
        'status',
    ];

    public static function allEvents()
    {
        return Event::select('event.*')
            ->join('users', 'users.id', '=', 'organizerid')
            ->whereIn('event.status', ['future', 'ongoing'])
            ->where('users.isdeleted', '=', '0')
            ->where('users.isbanned', '=', '0')
            ->orderBy('eventdatetime');
    }

    public static function allUserGoingEvents(User $user){
        return Event::select('event.*')
        ->join('users', 'users.id', '=', 'organizerid')
        ->join('attends', 'attends.event_id', '=', 'event.id')
        ->where('attends.user_id', $user->id)
        ->whereIn('event.status', ['future', 'ongoing'])
        ->where('users.isdeleted', '=', '0')
        ->where('users.isbanned', '=', '0')
        ->orderBy('eventdatetime');
    }

    public static function getEvents(User $user)
    {
        return Event::select('event.*')
            ->join('users', 'users.id', '=', 'organizerid')
            ->where('event.organizerid', $user->id)
            ->whereIn('event.status', ['future', 'ongoing'])
            ->where('users.isdeleted', '=', '0')
            ->where('users.isbanned', '=', '0')
            ->orderBy('eventdatetime');
    }

    public static function getPastEvents(User $user)
    {
        return Event::select('event.*')
            ->join('users', 'users.id', '=', 'organizerid')
            ->join('attends', 'attends.event_id', '=', 'event.id')
            ->where('attends.user_id', $user->id)
            ->where('event.status', 'closed')
            ->where('users.isdeleted', '=', '0')
            ->where('users.isbanned', '=', '0')
            ->orderBy('eventdatetime');
    }

    public static function getEventsOrganized(User $user)
    {
        return Event::select('event.*')
            ->join('users', 'users.id', '=', 'organizerid')
            ->where('event.organizerid', $user->id)
            ->where('event.status', 'closed')
            ->where('users.isdeleted', '=', '0')
            ->where('users.isbanned', '=', '0')
            ->orderBy('eventdatetime');

    }

    public static function getAllEventsFromDeletedOrganizers()
    {
        return Event::select('event.*')
            ->join('users', 'users.id', '=', 'organizerid')
            ->where('users.isdeleted', '=', '1')
            ->orderBy('eventdatetime');
    }

    public static function getAllEventsFromBannedOrganizers()
    {
        return Event::select('event.*')
            ->join('users', 'users.id', '=', 'organizerid')
            ->where('users.isbanned', '=', '1')
            ->orderBy('eventdatetime');
    }

    public function attendees() {
        return $this->hasMany('App\Models\Attendee');
    }

    public function topic(){
        return $this->hasOne('App\Models\Topic');
    }

    public function comments() {
        return $this->hasMany('App\Models\Comment');
    }

}