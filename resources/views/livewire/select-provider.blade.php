<div class="mt-5">
    <div>
        <x-input-label for="planned_date" :value="__('Date prévue')" class="text-white" />
        <input type="text" id="planned_date" name="planned_date" placeholder="Sélectionnez une date"
            class="input input-bordered input-warning w-full max-w-xs">
        <x-input-error class="mt-2 text-red-500" :messages="$errors->get('planned_date')" />
    </div>

    <div>
        <x-input-label for="max_end_date" :value="__('Date de fin limite (facultatif)')" class="text-white"/>
        <input type="text" id="max_end_date" name="max_end_date" placeholder="Sélectionnez une date" class="input input-bordered input-warning w-full max-w-xs">
        <x-input-error class="mt-2 text-red-500" :messages="$errors->get('max_end_date')" />
    </div>

    <x-input-label for="max_end_date" :value="__('Envoyer la proposition à :')" class="text-white" />
    <select class="select select-warning w-full max-w-xs" wire:model.live="selectedProvider" name="provider_id">
        <option value="" selected>Tous les prestataire disponibles</option>
        @foreach ($providers as $provider)
            <option value="{{ $provider->id }}">{{ $provider->name }}</option>
        @endforeach
    </select>
    <x-input-error class="mt-2 text-red-500" :messages="$errors->get('max_end_date')" />
</div>




<script>
    document.addEventListener('livewire:init', function() {
        const today = new Date();
        const minDate = new Date(today);
        minDate.setDate(today.getDate() + 2);

        const plannedDatePicker = flatpickr("#planned_date", {
            mode: "single",
            enableTime: true,
            dateFormat: "d-m-Y H:i",
            minDate: minDate,
            locale: "fr",
            onChange: function(selectedDates, dateStr, instance) {
                if (selectedDates.length > 0) {
                    // document.getElementById('max_end_date').disabled = false;
                    maxEndDatePicker.set('minDate', selectedDates[0]);
                } else {
                    // document.getElementById('max_end_date').disabled = false;
                }
            },
            onClose: function(selectedDates, dateStr) {
                @this.set('date', dateStr);
                // document.getElementById('max_end_date').disabled = false;
            }
        });

        const maxEndDatePicker = flatpickr("#max_end_date", {
            mode: "single",
            enableTime: true,
            dateFormat: "d-m-Y H:i",
            locale: "fr",
        });
    });
</script>
