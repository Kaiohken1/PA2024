<div>
    <div class="max-w-xl w-full flex flex-row flex-wrap">
        @foreach ($inputs as $key => $input)
            @if ($key == 0)
                <div wire:click="addInput"
                    class="flex items-center justify-center text-green-600 text-sm py-4 cursor-pointer ml-5">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <p class="ml-2">Ajouter un nouveau document requis</p>
                </div>
            @else
                <div class="mt-5 mr-5">
                    <div class="w-full flex">
                        <div>
                            <select id="documents"  name ="documentsId[]"
                                class="shadow-sm border-0 focus:outline-none p-3 block w-full sm:text-sm border-gray-300 rounded-md">
                                <option value="" disabled>Sélectionnez un type de donnée</option>
                                @foreach (\App\Models\Document::All() as $document)
                                    <option value="{{ $document->id }}">{{ $document->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @if ($key > 0)
                            <div wire:click="removeInput({{ $key }})"
                                class="flex items-center justify-end text-red-600 text-sm cursor-pointer ml-4">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <p>Supprimer le document</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        @endforeach
    </div>
</div>
