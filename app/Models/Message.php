<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'company',
        'subject',
        'event_date',
        'message',
        'is_read',
    ];

    protected $casts = [
        'event_date' => 'date',
        'is_read'    => 'boolean',
    ];
}
