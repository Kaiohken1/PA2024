<div>
    @if ($userId)
        <button class="btn btn-success mr-3" wire:click="message({{ $userId }})">
            Message
        </button>
    @else
        <p>Aucun utilisateur trouvé.</p>
    @endif
</div>