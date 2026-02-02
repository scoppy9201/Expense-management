<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiChatHistory extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'user_message', 'ai_response'];
    protected $table = 'ai_chat_history';
}
