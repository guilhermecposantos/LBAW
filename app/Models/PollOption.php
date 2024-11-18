<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

Class PollOption extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'polloption';

    protected $fillable = [
        'id', 'comment_id'
    ];

    public static function polloption(){
        return PollOption::select('polloption.id', 'polloption.comment_id')
        ->from('polloption')
        ->get();
    }

    public function votes() {
        return $this->hasMany(PollVote::class, 'option_id');
    }

    public function comment() {
        return $this->belongsTo(Comment::class, 'comment_id');
    }

    
    
}