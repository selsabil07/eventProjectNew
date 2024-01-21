<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    use HasFactory;
    public function EventManager()
    {
        return $this->belongsTo(User::class);
    }
    protected $fillable = [
        'EventManager_id',
        'eventTitle' ,
        'country',
        'sector',
        'photo',
        'tags',
        'summary',
        'description',
        'approved',
        'startingDate',
        'endingDate',
    ];
}
