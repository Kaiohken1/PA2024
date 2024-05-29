<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profil de l\'utilisateur') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <div class="px-6 flex flex-col overflow-x-auto mb-3">
                        <div class="flex flex-row ">
                                <div class="avatar">
                                    <div class="w-24 rounded-full">
                                        
                                        <img src="{{ $user->avatar != NULL ? Storage::url($user->avatar) : 'https://i0.wp.com/sbcf.fr/wp-content/uploads/2018/03/sbcf-default-avatar.png?w=300&ssl=1'}}" />
                                        
                                    </div>
                                </div>
                                    <div class="flex flex-col ">
                                        <span class="text-3xl font-extrabold px-2 text-left">{{ $user->first_name}} {{ $user->name }}</span>
                                        <span class="text-base font-bold px-2 text-left">{{ $user->display_city == 1 ? $user->ville : '' }}</span>
                                        <span class="text-base font-bold px-2 text-left">Membre depuis {{ Illuminate\Support\Carbon::parse($user->created_at)->translatedFormat('F Y') }}</span>
                                    </div>
                                </div>
                                <div class="flex flex-row">
                                
                                <span>bla bla bla</span>
                </div>
            </div>
        </div>
    </div>
    
</x-app-layout>
