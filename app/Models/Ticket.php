<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $table = 'ticket';

    protected $fillable = [
        'id', 
        'eventid',
        'userid',
        'price', 
        'paymentmethod',
    ];

    public static function tickets(){
        return Ticket::select('ticket.id', 'ticket.userid', 'ticket.eventid', 'ticket.price', 'ticket.paymentmethod')
        ->from('ticket')
        ->orderby('ticket.created_at', 'desc')
        ->get();
    }

    public static function ticketsFromUser(User $user){
        return Ticket::select('ticket.id', 'ticket.userid', 'ticket.eventid', 'ticket.price', 'ticket.paymentmethod')
        ->from('ticket')
        ->where('ticket.userid', $user->id)
        ->orderby('ticket.created_at', 'desc')
        ->get();
    }

    public static function ticketsFromEvent(Event $event){
        return Ticket::select('ticket.id', 'ticket.userid', 'ticket.eventid', 'ticket.price', 'ticket.paymentmethod')
        ->from('ticket')
        ->where('ticket.eventid', $event->id)
        ->orderby('ticket.created_at', 'desc')
        ->get();
    }

    public static function ticketsFromUserAndEvent(User $user, Event $event){
        return Ticket::select('ticket.id', 'ticket.userid', 'ticket.eventid', 'ticket.price', 'ticket.paymentmethod')
        ->from('ticket')
        ->where('ticket.userid', $user->id)
        ->where('ticket.eventid', $event->id)
        ->orderby('ticket.created_at', 'desc')
        ->get();
    }

}