<div class="flex h-full flex-col overflow-hidden bg-white sm:rounded-lg">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight text-center">{{ __('Chatbot') }}</h2>
    <div class="overcontainer p-6 shadow-sm  flex-grow overflow-y-auto" x-data="{ scroll: () => { $el.scrollTo(0, $el.scrollHeight); }}" x-intersect="scroll()" id="conversation">
        <div wire:poll.10000ms>
            @foreach ($messages as $message)
            <div class="chat @if($message->is_chatbot_message) chat-start @else chat-end @endif">
                <div class="chat-image avatar">
                    <div class="w-10 rounded-full">
                        @if($message->is_chatbot_message)
                        <img alt="Photo" src="https://cdn.dribbble.com/userupload/13167768/file/original-08e29755d8f12fdb9ef53d5b88bfeef0.jpg?resize=1024x768" />
                        @else
                        <img alt="Photo" src="{{$message->user->getImageUrl()}}" />
                        @endif

                    </div>
                </div>
                <div class="chat-header">
                    @if($message->is_chatbot_message)
                    Chatbot
                    @else
                    {{ $message->user->name }} {{ $message->user->first_name }}
                    @endif
                    <time class="text-xs opacity-50">{{ $message->created_at->diffForHumans() }}</time>
                </div>
                <div class="chat-bubble chat-content *:underline">{!! $message->message !!}</div>
            </div>
            @endforeach
            @if($lastMessageOfChatbot == 0)
            <div class="chat chat-start">
                <div class="chat-image avatar">
                    <div class="w-10 rounded-full">
                        <img alt="Photo" src="https://cdn.dribbble.com/userupload/13167768/file/original-08e29755d8f12fdb9ef53d5b88bfeef0.jpg?resize=1024x768" />
                    </div>
                </div>
                <div class="chat-header">
                    Chatbot
                    <time class="text-xs opacity-50"></time>
                </div>
                <div class="chat-bubble chat-content *:underline"><span class="loading loading-dots loading-sm"></span></div>
            </div>
            @endif
        </div>
    </div>

    <div class="p-6 bg-white shadow-sm sm:rounded-lg">
        <form wire:submit.prevent="sendMessage" class="flex flex-row items-center justify-center space-x-4">
            <textarea class="textarea textarea-bordered w-3/4 resize-none" rows="1" wire:model.debounce.2000m="message" placeholder="Envoyez votre message.." @if($lastMessageOfChatbot==0) disabled @endif></textarea>
            @error('message') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            <button class="btn btn-warning w-1/5" type="submit" @if($lastMessageOfChatbot==0) disabled @endif>Envoyer</button>
        </form>
    </div>
</div>