<?php

namespace App\Models;

use App\Models\User;
use App\Models\Event;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function exhibitor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected $fillable = [

        'name', 'description', 'price', 'quantity', 'event_id', 'photo', 'user_id',

    ];
}
