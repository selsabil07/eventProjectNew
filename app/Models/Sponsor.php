<?php

namespace App\Models;

use App\Models\Event;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sponsor extends Model
{
    use HasFactory;

    public function events(){
        return $this->belongsToMany(Event::class, 'event_id');
    }

    protected $fillable = [
        'event_id',
        'logo' ,
        'name',
    ];
}
