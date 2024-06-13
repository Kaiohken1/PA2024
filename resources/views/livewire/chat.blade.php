<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div wire:poll>
                        @foreach ($messages as $message)                   
                            <div class="chat @if($message->from_user_id == auth()->id()) chat-end @else chat-start @endif">
                                <div class="chat-image avatar">
                                    <div class="w-10 rounded-full">
                                        <img alt="Photo"
                                        src="{{ $message->fromUser->getImageUrl() }}" />
                                    </div>
                                </div>
                                <div class="chat-header">
                                    @if($message->fromUser->provider)
                                    {{$message->fromUser->provider->name}}
                                    @else
                                    {{$message->fromUser->name}} {{$message->fromUser->first_name}}
                                    @endif
                                    <time class="text-xs opacity-50">{{$message->created_at->diffForHumans()}}</time>
                                </div>
                                <div class="chat-bubble">{{$message->message}}</div>
                            </div>
                        @endforeach
                    </div>

                    <div class="form-control mt-4">
                        <form action="" wire:submit.prevent="sendMessage">
                            <textarea class="textarea textarea-bordered w-full" wire:model="message" placeholder="Envoyez votre message.."></textarea>
                            <button class="btn btn-warning mt-4" type="submit">Envoyer</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
