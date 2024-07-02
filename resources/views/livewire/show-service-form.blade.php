<div class="mt-3 mb-3">
    <div>
        <x-input-label for="service" :value="__('Service proposé')" />
        <select name="service_id" id="service_id" wire:model.change="service_id" class="select select-warning  w-full max-w-xs">
            <option disabled selected>Choissisez un service</option>
            @foreach (App\Models\Service::All() as $service)
                <option value="{{ $service->id }}">
                    {{ $service->name }}
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('service_id')" class="mt-2" />
    </div>

    @if ($service)
        <div>
            @if ($flexPrice)
                <p class="font-semibold mt-3">Le tarif de ce service est évolutif, nous vous invitons à nous envoyer votre
                    propre barème.</p>
                    <div>
                        <x-input-label :value="__('Barème proposé')" />
                        <input class="file-input w-full max-w-xs" id="bareme" type="file"
                            name="bareme">
                        <x-input-error :messages="$errors->get('bareme')" class="mt-2" />
                    </div>
            @else
                <p class="font-semibold">Tarif : <strong>{{ $price }}€</strong></p>
            @endif

            <h4 class="mt-2">Description : </h4>
            <p>{{ $description }}</p>

            <h4 class="mt-2">Documents et habilitations requises : </h4>
            @if ($documents)
                <ul class="list-disc list-inside mt-2">
                    @foreach ($documents as $document)
                        <div>
                            <x-input-label :value="$document->name" />
                            <input class="file-input w-full max-w-xs" id="{{ $document->name }}" type="file"
                                name="documents[{{$document->id}}]">
                            <x-input-error :messages="$errors->get('documents')" class="mt-2" />
                        </div>
                    @endforeach
                </ul>
            @endif
        </div>
    @endif

</div>
