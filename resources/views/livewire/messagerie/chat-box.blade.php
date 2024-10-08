<div 
    x-data="{
        height: 0,
        conversationElement: document.getElementById('conversation'),
        markAsRead: null
    }" 
    x-init="height = conversationElement.scrollHeight;
    $nextTick(() => conversationElement.scrollTop = height);
    Echo.private('users.{{ Auth()->user()->id }}')
        .notification((notification) => {
            if (notification['type'] == 'App\\Notifications\\MessageRead' && notification['conversation_id'] == {{ $this->selectedConversation->id }}) {
                markAsRead = true;
            }
        });"
    @scroll-bottom.window="
    $nextTick(()=>
    conversationElement.scrollTop= conversationElement.scrollHeight
    );"
    class="w-full overflow-hidden {{ Auth::user()->isAdmin() ? 'bg-gray-900 text-white' : 'bg-white text-black' }}"
>
    <div class="border-b {{ Auth::user()->isAdmin() ? 'border-gray-700' : 'border-gray-200' }} flex flex-col overflow-y-scroll grow h-full">
        <header class="w-full sticky inset-x-0 flex pb-[5px] pt-[5px] top-0 z-10 {{ Auth::user()->isAdmin() ? 'bg-gray-800 border-gray-700' : 'bg-white border-gray-200' }}">
            <div class="flex w-full items-center px-2 lg:px-4 gap-2 md:gap-5">
                <a class="shrink-0 lg:hidden" href="#">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 {{ Auth::user()->isAdmin() ? 'text-white' : 'text-black' }}">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15m0 0l6.75 6.75M4.5 12l6.75-6.75" />
                    </svg>
                </a>
                <div class="shrink-0">
                    <x-avatar class="h-9 w-9 lg:w-11 lg:h-11" src="{{ $selectedConversation->getReceiver()->provider ? $selectedConversation->getReceiver()->provider->avatar : $selectedConversation->getReceiver()->getImageUrl() }}" />
                </div>
                <h6 class="font-bold truncate {{ Auth::user()->isAdmin() ? 'text-white' : 'text-black' }}"> {{ $selectedConversation->getReceiver()->email }} </h6>
            </div>
        </header>

        <main
            @scroll="
            scropTop = $el.scrollTop;

            if(scropTop <= 0){

                $dispatch('loadMore');
            }"
            @update-chat-height.window="
            newHeight= $el.scrollHeight;

            oldHeight= height;
            $el.scrollTop= newHeight- oldHeight;
            height=newHeight;"
            id="conversation"
            class="flex flex-col gap-3 p-2.5 overflow-y-auto flex-grow overscroll-contain overflow-x-hidden w-full my-auto no-sc "
        >
            @if ($loadedMessages)
                @php
                    $previousMessage = null;
                @endphp

                @foreach ($loadedMessages as $key => $message)
                    @if ($key > 0)
                        @php
                            $previousMessage = $loadedMessages->get($key - 1);
                        @endphp
                    @endif

                    <div wire:key="{{ time() . $key }}" @class([
                        'max-w-[85%] md:max-w-[78%] flex w-auto gap-2 relative mt-2',
                        'ml-auto' => $message->sender_id === auth()->id(),
                    ])>
                        <div @class([
                            'shrink-0',
                            'invisible' => $previousMessage?->sender_id == $message->sender_id,
                            'hidden' => $message->sender_id === auth()->id(),
                        ])>
                            <x-avatar src="{{$message->conversation->getReceiver()->getImageUrl()}}" />
                        </div>

                        <div @class([
                            'flex flex-wrap text-[15px]  rounded-xl p-2.5 flex flex-col text-black bg-[#f6f6f8fb]',
                            'rounded-bl-none border border-gray-200/40' => !(
                                $message->sender_id === auth()->id()
                            ),
                            'rounded-br-none bg-blue-500/80 text-white' =>
                                $message->sender_id === auth()->id(),
                        ])>
                            <p class="whitespace-normal truncate text-sm md:text-base tracking-wide lg:tracking-normal">
                                {{ $message->body }}
                            </p>

                            <div class="ml-auto flex gap-2">
                                <p @class([
                                    'text-xs',
                                    'text-gray-500' => !($message->sender_id === auth()->id()),
                                    'text-white' => $message->sender_id === auth()->id(),
                                ])>
                                    {{ $message->created_at->format('H:i') }}
                                </p>
                                @if ($message->sender_id === auth()->id())
                                    <div x-data="{ markAsRead: @json($message->isRead()) }">
                                        <span x-cloak x-show="markAsRead" @class('text-yellow-400')>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check2-all" viewBox="0 0 16 16">
                                                <path d="M12.354 4.354a.5.5 0 0 0-.708-.708L5 10.293 1.854 7.146a.5.5 0 1 0-.708.708l3.5 3.5a.5.5 0 0 0 .708 0l7-7zm-4.208 7-.896-.897.707-.707.543.543 6.646-6.647a.5.5 0 0 1 .708.708l-7 7a.5.5 0 0 1-.708 0z" />
                                                <path d="m5.354 7.146.896.897-.707.707-.897-.896a.5.5 0 1 1 .708-.708z" />
                                            </svg>
                                        </span>
                                        <span x-show="!markAsRead" @class('text-gray-400')>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check2" viewBox="0 0 16 16">
                                                <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z" />
                                            </svg>
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </main>

        <footer class="shrink-0 z-10 {{ Auth::user()->isAdmin() ? 'bg-gray-800 border-t border-gray-700' : 'bg-white border-t border-gray-200' }}">
            <div class="p-2">
                <form x-data="{ body: $wire.entangle('body') }" @submit.prevent="$wire.sendMessage" method="POST" autocapitalize="off">
                    @csrf
                    <input type="hidden" autocomplete="false" style="display:none">
                    <div class="grid grid-cols-12">
                        <input x-model="body" type="text" autocomplete="off" autofocus placeholder="Écrivez votre message ici" maxlength="1700"
                            class="col-span-10 p-3 border-0 outline-0 focus:border-0 focus:ring-0 hover:ring-0 rounded-lg focus:outline-none {{ Auth::user()->isAdmin() ? 'bg-gray-700 text-white' : 'bg-gray-100 text-black' }}">
                        <button x-bind:disabled="!body.trim()" class="col-span-2 bg-yellow-400 rounded text-black" type='submit'>Envoyer</button>
                    </div>
                </form>
                @error('body')
                    <p> {{ $message }} </p>
                @enderror
            </div>
        </footer>
    </div>
</div>
