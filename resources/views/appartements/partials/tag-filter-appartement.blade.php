
 
    
    
    
        <form method="POST" action="{{ route('property.index') }}" enctype="multipart/form-data">
        @csrf

        @foreach($tags as $tag)
            <div class="flex flex-row items-center justify-between pt-2">
                <span >{{$tag->name}}</span>
                <input class="checkbox" value="{{$tag->id}}" type="checkbox" name="tag_id[]"/>
                {{--<input type="checkbox" aria-label="{{$tag->name}}" value="{{$tag->id}}" class="btn" />--}}
            </div>  
        @endforeach
        <x-primary-button class="mt-5">
        {{ __('Afficher filtre') }}
        </x-primary-button>
        </form>
    

    
