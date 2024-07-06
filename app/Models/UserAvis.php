<?php

namespace App\Models;

use App\Models\User;
use App\Models\Reservation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserAvis extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_user_id',
        'receiver_user_id',
        'reservation_id',
        'rating',
        'comment'
    ];

    public function sendUser()
    {
        return $this->belongsTo(User::class, 'sender_user_id');
    }

    public function receiveUser()
    {
        return $this->belongsTo(User::class, 'receiver_user_id');
    }

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
}
