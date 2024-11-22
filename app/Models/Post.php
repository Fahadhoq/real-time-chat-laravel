<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Post  extends Authenticatable
{
    protected $table = 'posts';

    protected $fillable = [
        'name',
    ];

    //one to one (Polymorphic)
    public function image()
    {
        return $this->morphOne(Images::class, 'imageable');
    }

    //for custome use
    // public function image()
    // {
    //     return $this->morphOne(Images::class, 'imageable', 'custom_type_column', 'custom_id_column');
    // }

    //one to many (Polymorphic)
    public function images()
    {
        return $this->morphMany(Images::class, 'imageable');
    }

     //many to many (Polymorphic)
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }


}
