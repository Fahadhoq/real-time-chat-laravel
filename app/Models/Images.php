<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Images  extends Authenticatable
{
    protected $table = 'images';

    public function imageable()
    {
        return $this->morphTo();
    }

    //for custome use
    // public function imageable(): MorphTo
    // {
    //     return $this->morphTo('imageable', 'custom_type_column', 'custom_id_column');
    // }


}
