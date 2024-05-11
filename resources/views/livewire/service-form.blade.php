<div class="mt-3 mb-3">
    <div class="mb-4">
        <label for="service" class="block text-sm font-medium text-gray-700">{{ __('Services disponibles') }}</label>
        @foreach ($services as $service)
            <div>
                <input type="checkbox" class="service-checkbox" data-service="{{ $service->id }}"
                    wire:model.change="selectedServices.{{ $service->id }}">
                <label>{{ $service->name }}</label>
                <p>{{$service->description}}</p>
            </div>


            @if (isset($selectedServices[$service->id]) && $selectedServices[$service->id] == true)
            <input type="hidden" name="services[]" value="{{ $service->id }}">
                <div id="service-form-{{ $service->id }}" class="mt-3 mb-3">
                    @foreach ($service->parameters as $parameter)
                        @switch($parameter->data_type_id)
                            @case(1)
                                <div>
                                    <p>{{ $parameter->name }}</p>
                                    <input type="text" value="{{ $appartement->address }}" class="bg-zinc-200"
                                        name="address[{{ $service->id }}][{{ $parameter->id }}]" readonly>
                                </div>
                            @break

                            @case(2)
                                <div>
                                    <p>{{ $parameter->name }}</p>
                                    <input type="text" value="{{ $appartement->surface }}" readonly class="bg-zinc-200"
                                        name="surface[{{ $service->id }}][{{ $parameter->id }}]">
                                </div>
                            @break

                            @case(3)
                                <div>
                                    <p>{{ $parameter->name }}</p>
                                    <input type="text" value="{{ $appartement->roomCount }}" readonly class="bg-zinc-200"
                                    name="roomCount[{{ $service->id }}][{{ $parameter->id }}]">
                                </div>
                            @break

                            @case(4)
                                <div>
                                    <p>{{ $parameter->name }}</p>
                                    <input type="text" placeholder="{{ $parameter->name }}"
                                        name="text[{{ $service->id }}][{{ $parameter->id }}]">
                                </div>
                            @break

                            @case(5)
                                <div>
                                    <p>{{ $parameter->name }}</p>
                                    <input type="number" placeholder="{{ $parameter->name }}"
                                        name="number[{{ $service->id }}][{{ $parameter->id }}]">
                                </div>
                            @break

                            @case(6)
                                <div>
                                    <p>{{ $parameter->name }}</p>
                                    <input type="email" placeholder="{{ $parameter->name }}" name="email[{{ $service->id }}][{{ $parameter->id }}]">
                                </div>
                            @break

                            @case(7)
                                <div>
                                    <p>{{ $parameter->name }}</p>
                                    <input type="tel" pattern="[0-9]{10}" placeholder="{{ $parameter->name }}" name="tel[{{ $service->id }}][{{ $parameter->id }}]">
                                </div>
                            @break

                            @case(8)
                                <div>
                                    <p>{{ $parameter->name }}</p>
                                    <textarea name="longText" class="block mt-1 w-full" placeholder="{{ $parameter->name }}" name="longText[{{ $service->id }}][{{ $parameter->id }}]"></textarea>
                                </div>
                            @break

                            @case(9)
                                <div>
                                    <p>{{ $parameter->name }}</p>
                                    <input type="date" placeholder="{{ $parameter->name }}" name="date[{{ $service->id }}][{{ $parameter->id }}]">
                                </div>
                            @break

                            @case(10)
                                <div>
                                    <input type="checkbox" name="checkbox[{{ $service->id }}][{{ $parameter->id }}]">
                                    {{ $parameter->name }}
                                </div>
                            @break

                            @default
                                <p>Erreur lors de la récupération du formulaire</p>
                            @break
                        @endswitch
                        
                    @endforeach
                </div>
                <div>
                    <p>{{__('Autre informations dont vous souhaiteriez nous faire part pour ce service (falcultatif)')}}</p>
                    <textarea name="description" class="block mt-1 w-full" name="description"></textarea>
                </div>
            @endif
        @endforeach
    </div>
</div>
