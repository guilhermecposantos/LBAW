<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

Class PollVote extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'pollvote';

    protected $fillable = [
        'id', 'option_id', 'user_id'
    ];

    public static function pollvote(){
        return PollVote::select('pollvote.id', 'pollvote.option_id', 'pollvote.user_id')
        ->from('pollvote')
        ->get();
    }

    public function pollOption() {
        return $this->belongsTo(PollOption::class, 'option_id');
    }
}