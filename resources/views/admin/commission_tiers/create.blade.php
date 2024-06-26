<x-admin-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-gray-900 dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden border border-gray-700 p-6">
               <div class="max-w-xl">
                    <x-auth-session-status class="mb-4" :status="session('status')" />
                    <form method="POST" action="{{ route('admin.commissions.store') }}">
                        @csrf

                        <div>
                            <x-input-label for="min_amount" :value="__('Montant minimum')" class="text-white" />
                            <input id="min_amount" class="form-input block mt-1 w-full" type="number" name="min_amount" />
                            <x-input-error :messages="$errors->get('min_amount')" class="mt-2" />
                        </div>

                        
                        <div>
                            <x-input-label for="max_amount" :value="__('Montant maximum')" class="text-white" />
                            <input id="max_amount" class="form-input block mt-1 w-full" type="number" name="max_amount" />
                            <x-input-error :messages="$errors->get('max_amount')" class="mt-2" />
                        </div>

                        
                        <div>
                            <x-input-label for="percentage" :value="__('Pourcentage')"  class="text-white"/>
                            <input id="percentage" class="form-input block mt-1 w-full" type="number" name="percentage" />
                            <x-input-error :messages="$errors->get('percentage')" class="mt-2" />
                        </div>

                        <button class=" mt-5 btn btn-warning">
                            {{ __('Créer un pourcentage commission') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
