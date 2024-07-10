<div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Détails événement</h3>
                        @if($intervention)
                            <div class="mt-2">
                                <p><span class="text-gray-600">Prestataire</span> : {{ $intervention->provider->name }}</p>
                                <p><span class="text-gray-600">Client</span> : {{ $intervention->user->name }} {{ $intervention->user->first_name }}</p>
                                <p><span class="text-gray-600">Adresse de l'appartement :</span> {{ $intervention->appartement->address }}</p>
                                <br>
                                @foreach ($intervention->service_parameters as $parameter)
                                    <div class="flex">
                                        @php
                                        $version = Mpociot\Versionable\Version::find($parameter->pivot->parameter_version);
                                        @endphp
                                        @if($version->getModel()->data_type_id != 1)
                                            <p class="mr-3"><span class="text-gray-600">{{ $version->getModel()->name}}</span> : {{ $parameter->pivot->value }}</p>
                                        @endif
                                    </div>
                                @endforeach

                                @if($intervention->description)<p><span class="text-gray-600">Demande particulière:</span> {{ $intervention->description }}</p>@endif
                            </div>
                        @else
                            <div class="mt-2">
                                <p>Chargement des détails de l'intervention...</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-yellow-500 text-base font-medium text-white hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 sm:ml-3 sm:w-auto sm:text-sm" onclick="closeModal()">Fermer</button>
            </div>
        </div>
</div>
