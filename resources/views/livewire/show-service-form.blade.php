<div class="mt-3 mb-3">
    <div>
        <x-input-label for="service" :value="__('Service proposé')" />
        <select name="service_id" id="service_id" wire:model.change="service_id" class="select select-bordered w-full max-w-xs">
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
            <h4 class="mt-2">Description : </h4>
            <p>{{ $description }}</p>
        
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

            <h4 class="mt-2">Documents et habilitations requises : </h4>
            @if ($documents)
                <ul class="list-disc list-inside mt-2">
                    @foreach ($documents as $document)
                        <div class>
                            <x-input-label :value="$document->name" />
                            <input class="file-input w-full max-w-xs" id="{{ $document->name }}" type="file"
                                name="documents[{{$document->id}}]">
                            <x-input-error :messages="$errors->get('documents')" class="mt-2" />
                        </div>
                        <span>{{$document->description}}</span>

                    @endforeach
                </ul>
            @endif

            <div>
                <x-input-label :value="__('Informations bancaires')" />
                <input class="file-input w-full max-w-xs" id="iban" type="file"
                    name="iban">
                <x-input-error :messages="$errors->get('bareme')" class="mt-2" />
            </div>
            <span>Format pdf, png ou jpg</span>
        </div>
    @endif

</div>
