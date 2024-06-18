<div class="flex h">
    <div class="w-1/4 bg-white p-0 m-0 border-r  overflow-y-auto">
        @livewire('chat-list')
    </div>
    <div class="w-3/4 p-0 m-0 flex flex-col">
        <h1 class="text-xl font-bold bg-white flex justify-center">@if($user->provider) {{$user->provider->name}} @else{{$user->name}} {{$user->first_name}}@endif - #{{$intervention->id}}</h1>
        <div class="flex-grow overflow-y-auto p-6 bg-white shadow-sm sm:rounded-lg">
            <div wire:poll>
                @foreach ($messages as $message)
                    <div class="chat @if($message->from_user_id == auth()->id()) chat-end @else chat-start @endif">
                        <div class="chat-image avatar">
                            <div class="w-10 rounded-full">
                                <img alt="Photo" src="{{ $message->fromUser->getImageUrl() }}" />
                            </div>
                        </div>
                        <div class="chat-header">
                            @if($message->fromUser->provider)
                                {{ $message->fromUser->provider->name }}
                            @else
                                {{ $message->fromUser->name }} {{ $message->fromUser->first_name }}
                            @endif
                            <time class="text-xs opacity-50">{{ $message->created_at->diffForHumans() }}</time>
                        </div>
                        <div class="chat-bubble">{{ $message->message }}</div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="p-6 bg-white shadow-sm sm:rounded-lg" style="height: 200px;">
            <form action="" wire:submit.prevent="sendMessage" class="flex flex-col">
                <textarea class="textarea textarea-bordered w-full" wire:model="message" placeholder="Envoyez votre message.." @if($intervention->statut_id == 3) disabled @endif></textarea>
                @error('message') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                <button class="btn btn-warning mt-4" type="submit" @if($intervention->statut_id == 3) disabled @endif>Envoyer</button>
            </form>
        </div>
    </div>
</div>
