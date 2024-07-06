<div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
    <!-- Background overlay -->
    <div class="fixed inset-0 transition-opacity" aria-hidden="true">
        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
    </div>
    <!-- Centering trick for older browsers -->
    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
    <!-- Modal container -->
    <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
        <div class="px-4 pt-5 pb-4 bg-white sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
                <div class="mt-3 text-center sm:mt-0 sm:text-left">
                    <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">Fermeture</h3>
                    @if($fermeture)
                        <div class="mt-2">
                            <input type="hidden" id="closureDate">
                            <label for="closureReason" class="block text-sm font-medium text-gray-700">Raison</label>
                            <span>{{$fermeture->comment}}</span>
                        </div>
                    @else
                        <div class="mt-2">
                            <p>Chargement des détails de la fermeture...</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="px-4 py-3 bg-gray-50 sm:px-6 sm:flex sm:flex-row-reverse">
            @if($fermeture)<button type="button" class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-yellow-400 border border-transparent rounded-md shadow-sm hover:bg-yellow-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm" onclick="deleteClosure({{$fermeture->id}})">Supprimer</button>@endif
            <button type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-yellow-500 text-base font-medium text-white hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 sm:ml-3 sm:w-auto sm:text-sm" onclick="closeModal('Closure')">Fermer</button>
        </div>
    </div>
</div>