<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatbotMessage extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'message', 'is_chatbot_message', 'intervention_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
