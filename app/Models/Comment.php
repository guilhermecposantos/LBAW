<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $table = 'comment';

    protected $fillable = [
        'id', 'user_id', 'event_id', 'content', 'ispoll'
    ];

    public static function comments(){
        return Comment::select('comment.id', 'comment.user_id', 'comment.event_id', 'comment.content', 'comment.ispoll')
        ->from('comment')
        ->orderby('comment.created_at')
        ->get();
    }

    public function pollOptions() {
        if ($this->ispoll) {
            return $this->hasMany(PollOption::class, 'comment_id');
        }
        else{
            return null;
        }
    }


}