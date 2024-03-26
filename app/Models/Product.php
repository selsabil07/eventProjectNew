<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    public function exhibitor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected $fillable = [

        'name', 'description', 'price', 'quantity', 'user_id', 'photo',

    ];
}
