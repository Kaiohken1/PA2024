<div>
    <div class="flex items-center">
        <label class="flex items-center cursor-pointer">
            <input type="checkbox" class="toggle toggle-warning" wire:click="toggleAvailability" {{ $isAvailable ? 'checked' : '' }}>
            <span class="ml-2 text-lg font-medium">{{ $isAvailable ? 'JE SUIS ACTUELLEMENT DISPONIBLE' : 'JE NE SUIS PAS DISPONIBLE' }}</span>
        </label>
    </div>
</div>
