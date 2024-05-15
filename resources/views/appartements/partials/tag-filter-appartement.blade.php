
    <form method="POST" action="{{ route('property.index') }}" enctype="multipart/form-data">
    @csrf

    
    
    <div class="flex flex-col" >
        @foreach($tags as $tag)
            <div class="flex flex-row items-center" >
                <x-text-input value="{{$tag->id}}" type="checkbox" name="tag_id[]"/>
                <span>{{$tag->name}}</span>
            </div>  
        @endforeach
    </div>

    <x-primary-button class="ms-3 mt-5 ml-0">
        {{ __('Afficher filtre') }}
    </x-primary-button>
</form>
