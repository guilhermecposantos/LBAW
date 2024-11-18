<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JoinRequest extends Model
{
    public $timestamps = false;

    protected $fillable = ['user_id', 'event_id', 'approved'];

    protected $table = 'join_request';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}