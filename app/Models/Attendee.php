<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendee extends Model
{
    use HasFactory;

    public $timestamps  = false;
    protected $table = 'attends';
    
    protected $fillable = [
        'userid', 'eventid'
    ]; 

    public function user() {
        return User::find($this->userid);
    }

    public function event() {
        return Event::find($this->eventid);
    }
}