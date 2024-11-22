<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Phone extends Authenticatable
{
    protected $table = 'phone';

    protected $fillable = [
        'number',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function simName()
    {
        return $this->hasOne(SimName::class);
    }
}
