<div>
    <div class="mb-4">
        <label for="category" class="block text-sm font-medium {{ Auth()->user()->isAdmin() ? 'text-white' :'text-gray-700'}}">{{ __('Sélectionnez une catégorie') }}</label>
        <select id="category" class="select select-warning w-full max-w-xs" wire:model.live="selectedCategory" wire:change="updateCat" class="block w-full mt-1">
            <option value="">{{ __('-- Sélectionnez une catégorie --') }}</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
    </div>

    @if (!empty($services))
        <div class="mb-4">
            <label for="service" class="block text-sm font-medium {{ Auth()->user()->isAdmin() ? 'text-white' :'text-gray-700'}}">{{ __('Services disponibles') }}</label>
                <select id="service" class="select select-warning w-full max-w-xs" wire:model.live="selectedService">
                    <option value="">-- Choisir un service --</option>
                    @foreach($services as $service)
                        <option value="{{ $service->id }}">{{ $service->name }}</option>
                    @endforeach
                </select>
            @foreach ($services as $service)

                @if ($selectedService == $service->id)
                    <input type="hidden" name="services[]" value="{{ $service->id }}">
                    <div id="service-form-{{ $service->id }}" class="mt-3 mb-3">
                        @foreach ($service->parameters as $parameter)
                            @switch($parameter->data_type_id)
                                @case(1)
                                    <div>
                                        <p class="{{ Auth()->user()->isAdmin() ? 'text-white' :'text-gray-700'}}">{{ $parameter->name }}</p>
                                        <input type="text" value="{{ $appartement->address }}" class="input input-bordered input-warning w-full max-w-xs bg-zinc-100"
                                            name="address[{{ $service->id }}][{{ $parameter->id }}]" readonly>
                                    </div>
                                @break

                                @case(2)
                                    <div>
                                        <p class="{{ Auth()->user()->isAdmin() ? 'text-white' :'text-gray-700'}}">{{ $parameter->name }}</p>
                                        <input type="text" value="{{ $appartement->surface }}" readonly class="input input-bordered input-warning w-full max-w-xs bg-zinc-100"
                                            name="surface[{{ $service->id }}][{{ $parameter->id }}]">
                                    </div>
                                @break

                                @case(3)
                                    <div>
                                        <p class="{{ Auth()->user()->isAdmin() ? 'text-white' :'text-gray-700'}}">{{ $parameter->name }}</p>
                                        <input type="text" value="{{ $appartement->roomCount }}" readonly class="input input-bordered input-warning w-full max-w-xs bg-zinc-100"
                                        name="roomCount[{{ $service->id }}][{{ $parameter->id }}]">
                                    </div>
                                @break

                                @case(4)
                                    <div>
                                        <p class="{{ Auth()->user()->isAdmin() ? 'text-white' :'text-gray-700'}}">{{ $parameter->name }}</p>
                                        <input type="text" placeholder="{{ $parameter->name }}"
                                            name="text[{{ $service->id }}][{{ $parameter->id }}]"
                                            class="input input-bordered input-warning w-full max-w-xs">
                                    </div>
                                @break

                                @case(5)
                                    <div>
                                        <p class="{{ Auth()->user()->isAdmin() ? 'text-white' :'text-gray-700'}}">{{ $parameter->name }}</p>
                                        <input type="number" placeholder="{{ $parameter->name }}"
                                            name="number[{{ $service->id }}][{{ $parameter->id }}]"
                                            class="input input-bordered input-warning w-full max-w-xs">
                                    </div>
                                @break

                                @case(6)
                                    <div>
                                        <p class="{{ Auth()->user()->isAdmin() ? 'text-white' :'text-gray-700'}}">{{ $parameter->name }}</p>
                                        <input type="email" placeholder="{{ $parameter->name }}" name="email[{{ $service->id }}][{{ $parameter->id }}]"
                                        class="input input-bordered input-warning w-full max-w-xs">
                                    </div>
                                @break

                                @case(7)
                                    <div>
                                        <p class="{{ Auth()->user()->isAdmin() ? 'text-white' :'text-gray-700'}}">{{ $parameter->name }}</p>
                                        <input type="tel" pattern="[0-9]{10}" placeholder="{{ $parameter->name }}" name="tel[{{ $service->id }}][{{ $parameter->id }}]"
                                        class="input input-bordered input-warning w-full max-w-xs">
                                    </div>
                                @break

                                @case(8)
                                    <div>
                                        <p class="{{ Auth()->user()->isAdmin() ? 'text-white' :'text-gray-700'}}">{{ $parameter->name }}</p>
                                        <textarea name="longText" class="block mt-1 w-full" placeholder="{{ $parameter->name }}" name="longText[{{ $service->id }}][{{ $parameter->id }}]"></textarea>
                                    </div>
                                @break

                                @case(9)
                                    <div>
                                        <p class="{{ Auth()->user()->isAdmin() ? 'text-white' :'text-gray-700'}}">{{ $parameter->name }}</p>
                                        <input type="date" placeholder="{{ $parameter->name }}" name="date[{{ $service->id }}][{{ $parameter->id }}]"
                                        class="input input-bordered input-warning w-full max-w-xs">
                                    </div>
                                @break

                                @case(10)
                                    <div>
                                        <p class="{{ Auth()->user()->isAdmin() ? 'text-white' :'text-gray-700'}}">{{ $parameter->name }}</p>
                                        <input type="checkbox" name="checkbox[{{ $service->id }}][{{ $parameter->id }}]" class="checkbox checkbox-warning">
                                    </div>
                                @break

                                @default
                                    <p>Erreur lors de la récupération du formulaire</p>
                                @break
                            @endswitch
                        @endforeach
                    </div>
                        {{-- <div>
                            <x-input-label for="planned_date" :value="__('Date prévue')"/>
                            <input type="datetime-local" id="planned_date" name="planned_date"
                                class="input input-bordered w-full max-w-xs" min="1" />
                            <x-input-error class="mt-2" :messages="$errors->get('planned_date')" />
                        </div>
                        <div>
                            <p>{{ __('Autre information dont vous souhaiteriez nous faire part (facultatif)') }}</p>
                            <textarea name="description[{{ $service->id }}]" class="block mt-1 w-full"></textarea>
                        </div> --}}
                @endif
            @endforeach
        </div>
    @endif
</div>