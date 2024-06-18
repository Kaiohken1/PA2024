<div>
    <ul>
        @foreach ($conversations as $conversation)
            <li class="p-2 border-b relative {{ request()->route('intervention')->id == $conversation['intervention']->id ? 'bg-gray-200' : '' }}">
                <a href="{{ route('interventions.chat', ['intervention' => $conversation['intervention']->id, 'user' => $conversation['user']->id]) }}" class="flex items-center">
                    <img src="{{ $conversation['user']->getImageUrl() }}" alt="Photo" class="w-10 h-10 rounded-full mr-3">
                    <div>
                        <div class="font-bold">
                            @if($conversation['user_name'])
                                {{ $conversation['user_name'] }}
                            @else 
                                {{ $conversation['user']->name }} {{ $conversation['user']->first_name }}
                            @endif
                            - #{{ $conversation['intervention']->id }}                            
                            @if ($conversation['notifs'] > 0)
                                <span class="bg-red-500 text-white text-xs rounded-full px-2 py-1 ml-2">
                                    {{ $conversation['notifs'] }}
                                </span>
                            @endif
                        </div>
                        <div class="text-sm text-gray-600">{{ $conversation['last_message']->message }}</div>
                        <div class="text-xs text-gray-400">{{ $conversation['last_message']->created_at->diffForHumans() }}</div>
                    </div>
                </a>
            </li>
        @endforeach
    </ul>
</div>
