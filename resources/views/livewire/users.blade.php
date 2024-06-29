<div>
    @if ($user)
        <button class="btn btn-success mr-3" wire:click="message({{ $user->id }})">
            Message
        </button>
    @else
        <p>Aucun utilisateur trouvé.</p>
    @endif
</div>