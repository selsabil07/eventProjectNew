<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    use HasFactory;

    // public function eventManager()
    // {
    //     return $this->belongsTo(User::class)->whereHas('roles', function ($query) {
    //         $query->where('name', 'eventManager');
    //     });
    // }
    public function eventManager()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // public function exhibitors()
    // {
    //     return $this->hasMany(User::class)->whereHas('roles', function ($query) {
    //         $query->where('name', 'exhibitor');
    //     });
    // }

    public function exhibitors()
    {
        return $this->belongsToMany(User::class, 'event_user')->wherePivot('role', 'exhibitor');
    }




    protected $fillable = [
        
        'user_id',
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
