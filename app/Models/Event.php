<?php

namespace App\Models;

use App\Models\Tag;
use App\Models\User;
use App\Models\Sponsor;
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

    public function sponsors()
    {
        return $this->hasMany(Sponsor::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function exhibitors()
    {
        return $this->belongsToMany(User::class, 'event_user', 'event_id', 'user_id')->wherePivot('role', 'exhibitor');
    }




    protected $fillable = [
        
        'user_id',
        'eventTitle' ,
        'country',
        'sector',
        'city',
        'address',
        'photo',
        // 'tags',
        'summary',
        'description',
        'startingDate',
        'endingDate',
    ];
}
