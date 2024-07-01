<div class="{{ Auth::user()->isAdmin() ? 'bg-gray-900' : 'bg-white' }} fixed h-full flex border lg:shadow-sm overflow-hidden inset-0 lg:top-16 lg:inset-x-2 m-auto lg:h-[90%] rounded-t-lg">
    <div class="relative w-full md:w-[320px] xl:w-[400px] overflow-y-auto shrink-0 h-full border">
        <livewire:messagerie.chat-list />
    </div>

    <div class="hidden md:grid w-full border-l h-full relative overflow-y-auto" style="contain:content">
        <div class="m-auto text-center justify-center flex flex-col gap-3">
            <h4 class="font-medium text-lg {{ Auth::user()->isAdmin() ? 'text-white' : '' }}">Choisissez une conversation</h4>
        </div>
    </div>
</div>