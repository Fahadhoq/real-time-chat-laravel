<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

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

    public function phone()
    {
        return $this->hasOne(Phone::class);
    }

    public function phones()
    {
        return $this->hasMany(Phone::class);
    }

    /**
     * Get the user's largest Priority.
     */
    public function largestPriorityPhone()
    {
        return $this->hasOne(Phone::class)->ofMany('Priority', 'min');
    }

    /**
     * Get the sim name.
     */
    public function simName()
    {
        return $this->hasOneThrough(SimName::class, Phone::class);
    }
    
    //many to many
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_role', 'user_id', 'role_id')->as('user_role');
        // ->as('user_role') by defult pivot but change 
    }

    // One to One (Polymorphic)
    public function image(): MorphOne
    {
        return $this->morphOne(Images::class, 'imageable');
    }

    public function notifications()
    {
        return $this->morphMany(DatabaseNotification::class, 'notifiable')->orderBy('created_at', 'desc');
    }

}
