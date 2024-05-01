<x-app-layout>
    <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
        <form method="POST" action="{{ route('interventions.store', ['id' => $selectedAppartement]) }}">
            @csrf

            <input type="hidden" name="appartement_id" value="{{ $selectedAppartement->id }}">

            <label for="service" class="block text-sm font-medium text-gray-700">Service</label>
            <select name="service_id" id="service_id">
                @foreach ($services as $service)
                    <option value="{{$service->id}}" data-price="{{$service->price}}">{{$service->name}}</option>
                @endforeach
            </select>
            
            <p><strong>Prix du service :</strong> <span id="prix_service">{{$services[0]->price}}</span>€</p>

            <div class="mt-4">
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" id="description" placeholder="{{ __('Si vous avez des précisions dont vous souhaiteriez faire part') }}" class="mt-1 focus:ring focus:ring-yellow-200 focus:ring-opacity-50 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                <x-input-error :messages="$errors->get('description')" class="mt-2" />
            </div>
            <x-primary-button class="mt-4">{{ __('Demander une intervention') }}</x-primary-button>
        </form>
    </div>
</x-app-layout>


<script>
    document.getElementById('service_id').addEventListener('change', function() {
        var selectedOption = this.options[this.selectedIndex];
        var price = selectedOption.getAttribute('data-price');
        document.getElementById('prix_service').innerText = price;
    });
</script>
