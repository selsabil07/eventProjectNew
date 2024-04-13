<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Product;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    public function events()
    {
        if ($this->hasRole('eventManager')) {
            return $this->hasMany(Event::class, 'user_id');
        }

    }

    public function products()
    {
        if ($this->hasRole('exhibitor')) {
            return $this->hasMany(Product::class, 'user_id');
        }

    }

    // public function event()
    // {
    //     if ($this->hasRole('exhibitor')) {
    //         return $this->hasMany(Event::class);
    //     }

    // }

    
    public function event()
    {
        return $this->belongsToMany(Event::class ,'event_user', 'user_id', 'event_id');
    }


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'event_id',
        'user_name',
        'first_name',
        'last_name',
        'birthday',
        'email',
        'phone',
        'organization',
        'profile_photo',
        'password',
    ];
    protected $table = 'users';


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
