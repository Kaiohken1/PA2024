<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use App\Models\Conversation;

class Users extends Component
{
    public $userId;
    public function message($userId)
    {
      //  $createdConversation =   Conversation::updateOrCreate(['sender_id' => auth()->id(), 'receiver_id' => $userId]);

      $authenticatedUserId = auth()->id();

      $existingConversation = Conversation::where(function ($query) use ($authenticatedUserId, $userId) {
                $query->where('sender_id', $authenticatedUserId)
                    ->where('receiver_id', $userId);
                })
            ->orWhere(function ($query) use ($authenticatedUserId, $userId) {
                $query->where('sender_id', $userId)
                    ->where('receiver_id', $authenticatedUserId);
            })->first();
        
      if ($existingConversation) {
        return redirect()->route('admin.chat', ['query' => $existingConversation->id]);
      }
  
      $createdConversation = Conversation::create([
          'sender_id' => $authenticatedUserId,
          'receiver_id' => $userId,
      ]);
 
        return redirect()->route('chat', ['query' => $createdConversation->id]);
        
    }

    public function render()
    {    
        return view('livewire.users', ['userId' => $this->userId]);
    }
}
