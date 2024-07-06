<div>
    <section class="mt-10">
        <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
            <div class="bg-white text-gray-900 relative shadow-md sm:rounded-lg overflow-hidden border border-gray-300">
                <div class="flex items-center justify-between p-4 border-b border-gray-300">
                    <div class="flex">
                        <div class="relative w-full">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg aria-hidden="true" class="w-5 h-5 text-gray-500"
                                    fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input 
                                wire:model.live.debounce.300ms="search"
                                type="text"
                                class="bg-gray-200 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2"
                                placeholder="Rechercher" required>
                        </div>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    @if(!$fermetures->isEmpty())
                        <table class="w-full text-sm text-left text-gray-900">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                                <tr>
                                    @include('livewire.includes.table-sort', ['name' => 'start', 'displayName' => 'DATE DE DEBUT'])
                                    @include('livewire.includes.table-sort', ['name' => 'end', 'displayName' => 'DATE DE FIN'])
                                    <th scope="col" class="px-4 py-3">Raison</th>
                                    <th scope="col" class="px-4 py-3"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($fermetures as $fermeture)
                                    <tr class="border-b border-gray-300">

                                        <td class="px-4 py-3 font-medium whitespace-nowrap">
                                            {{ \Carbon\Carbon::parse($fermeture->start)->format('d/m/Y') }}
                                        </td>
                                        <td class="px-4 py-3 font-medium whitespace-nowrap">
                                            {{ \Carbon\Carbon::parse($fermeture->end)->format('d/m/Y') }}
                                        </td>
                                        <td class="px-4 py-3 font-medium whitespace-nowrap">
                                            {{ $fermeture->comment }}
                                        </td>
                                        <td class="px-4 py-3 font-medium whitespace-nowrap">
                                            <button wire:click="delete({{$fermeture->id}})" class="btn btn-error mr-3">X</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        {{ $fermetures->links() }}
                    @else
                        <p class="p-4">Aucune fermeture</p>
                    @endif
                </div>

                <div class="py-4 px-3">
                    <div class="flex">
                        <div class="flex space-x-4 items-center mb-3">
                            <label class="w-32 text-sm font-medium text-gray-900">Par Page</label>
                            <select
                                wire:model.live='perPage'
                                class="bg-gray-200 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
