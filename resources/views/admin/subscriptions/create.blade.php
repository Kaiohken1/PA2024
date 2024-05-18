<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl  leading-tight text-white">
            {{ __('Créer un type d\'abonnement') }}
        </h2>

        @if (session('success'))
            <div class="p-4 mb-3 mt-3 text-center text-sm text-green-800 rounded-lg bg-green-50 dark:text-green-600"
                role="alert">
                {{ session('success') }}
            </div>
        @elseif (session('error'))
            <div class="p-4 mb-3 mt-3 text-center text-sm text-red-800 rounded-lg bg-red-50 dark:text-red-600"
                role="alert">
                {{ session('error') }}
            </div>
        @endif
    </x-slot>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-grey-800 border shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <form action="{{ route('subscriptions.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div>
                            <x-input-label for="name" :value="__('Nom du l\'abonnement')" class="text-white" />
                            <input id="name" name="name" type="text"
                                class="input input-bordered w-full max-w-xs mb-3" />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div>
                            <x-input-label for="monthly_price" :value="__('Tarif mensuel')" class="text-white" />
                            <input id="monthly_price" name="monthly_price" type="number"
                                class="input input-bordered w-full max-w-xs mb-3" />
                            <x-input-error class="mt-2" :messages="$errors->get('monthly_price')" />
                        </div>

                        <div>
                            <x-input-label for="annual_price" :value="__('Tarif annuel')" class="text-white" />
                            <input id="annual_price" name="annual_price" type="number"
                                class="input input-bordered w-full max-w-xs mb-3" />
                            <x-input-error class="mt-2" :messages="$errors->get('annual_price')" />
                        </div>

                        <div>
                            <x-input-label for="permanent_discount" :value="__('Montant de la remise permanante')" class="text-white" />
                            <input id="permanent_discount" name="permanent_discount" type="number"
                                class="input input-bordered w-full max-w-xs mb-3" />
                            <x-input-error class="mt-2" :messages="$errors->get('permanent_discount')" />
                        </div>

                        <div>
                            <x-input-label for="renewal_bonus" :value="__('Montant du bonus de renouvellement')" class="text-white" />
                            <input id="renewal_bonus" name="renewal_bonus" type="number"
                                class="input input-bordered w-full max-w-xs mb-3" />
                            <x-input-error class="mt-2" :messages="$errors->get('renewal_bonus')" />
                        </div>

                        <div>
                            <x-input-label for="logo" :value="__('Logo')" class="text-white" />
                            <input type="file" id="logo" name="logo" class="file-input w-full max-w-xs" />
                            <x-input-error :messages="$errors->get('logo')" class="mt-2" />
                        </div>

                        <div class="flex items-center gap-4 mt-5">
                            <button class="btn btn-active btn-neutral">Valider</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


</x-admin-layout>