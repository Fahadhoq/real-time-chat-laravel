<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DatabaseNotification extends Model
{
    use HasFactory;

    // If you're overriding the default table name, specify it
    protected $table = 'notifications';

    // Fillable fields
    protected $fillable = [
        'id',
        'type',
        'notifiable_type',
        'notifiable_id',
        'data',
        'read_at',
    ];

    // Ensure the `data` attribute is cast to an array
    protected $casts = [
        'data' => 'array',
    ];

    // Optional: Define relationships if necessary
    public function notifiable()
    {
        return $this->morphTo();
    }
}
