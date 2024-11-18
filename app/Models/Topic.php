<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'topic';

    protected $fillable = [
        'id', 'title'
    ];

    public static function topics()
    {
        return Topic::select('topic.id', 'topic.title')
            ->from('topic')
            ->orderBy('id')
            ->get();
    }
    
}