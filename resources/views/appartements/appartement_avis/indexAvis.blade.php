<div class="w-full bg-white shadow-md rounded my-4">
<h1 class=" py-3 px-6 text-2xl font-extrabold">{{__('Avis des voyageurs')}}</h1>
<h2 class=" px-6 text-xl font-extrabold size-max inline-flex"><x-ri-star-fill class="size-1/12"/>{{ $appartement->overall_rating }} | {{ $appartement->avis_count }} {{__('Avis')}}</h2>
<div class="py-3 flex flex-row">
    <div class="flex flex-col">
        <span class="px-6 text-xl font-extrabold">{{__('Propreté')}}</span>
        <span class="px-6 text-xl font-extrabold">{{ number_format($appartement->avis_avg_rating_cleanness, 2) }}</span>
    </div>
    <div class="flex flex-col">
        <span class="px-6 text-xl font-extrabold">{{__('Qualité/Prix')}}</span>
        <span class="px-6 text-xl font-extrabold">{{ number_format($appartement->avis_avg_rating_price_quality, 2) }}</span>
    </div>
    <div class="flex flex-col">
        <span class="px-6 text-xl font-extrabold">{{__('Emplacement')}}</span>
        <span class="px-6 text-xl font-extrabold">{{ number_format($appartement->avis_avg_rating_location, 2) }}</span>
    </div>
    <div class="flex flex-col">
        <span class="px-6 text-xl font-extrabold">{{__('Communication')}}</span>
        <span class="px-6 text-xl font-extrabold">{{ number_format($appartement->avis_avg_rating_communication, 2) }}</span>
    </div>
</div>
<div>



                    @foreach ( $appartementAvis as $avis)
             <div class="px-6 flex flex-col border-b-2 border-grey overflow-x-auto mb-3">
             <div class="flex flex-row ">

                                <div class="avatar">
                                    <div class="w-12 rounded-full">
                                        <a href="{{route('users.show', ['user' => $avis->reservation->user->id])}}">
                                        <img src="{{ $avis->reservation->user->avatar != NULL ? Storage::url($avis->reservation->user->avatar) : 'https://i0.wp.com/sbcf.fr/wp-content/uploads/2018/03/sbcf-default-avatar.png?w=300&ssl=1'}}" />
                                        </a>
                                    </div>
                                </div>
                                <a href="{{route('users.show', ['user' => $avis->reservation->user->id])}}" class="h-1/2">
                                    <span class="text-lg font-extrabold px-2 text-left">{{ $avis->reservation->user->first_name}} {{ $avis->reservation->user->name }}</span>
                                </a>
                                </div>
                                <div class="flex flex-row">
                                <div class="py-1 rating rating-sm">
                                @for ($i = 1; $i <= 5; $i++)
                                    <input type="radio" class="mask mask-star cursor-default" value="{{ $i }}" disabled {{ $avis->voyageur_rating == $i ? 'checked' : '' }}/>
                                @endfor
                                </div>
                                <span>{{Illuminate\Support\Carbon::parse($avis->reservation->end_time)->translatedFormat('F Y')}}</span>
                                @if(Auth::check() && $avis->reservation->user_id == auth()->user()->id)
                                    <div>
                                        <form action="{{ route('avis.destroy', ['appartement' => $avis->appartement_id, 'avi' => $avis->id]) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet avis ?')">
                                                <x-far-trash-alt class=" w-6 h-6"/>
                                            </button>
                                        </form>
                                    </div>

                                    <div class="px-2">
                                        <a href="{{ route('avis.edit', ['appartement' => $avis->appartement_id, 'avis' => $avis->id]) }}"><x-far-edit class="w-6 h-6"/></a>
                                    </div>
                                
                                @endif
                                </div>
                                <span class="py-3 text-left">{{ $avis->comment }}</span>
                                </div>
                                
                                
                                
</div>
                    @endforeach


                    

</div>
<div>
