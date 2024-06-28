<div>
    @if ($user)
        <button class="btn btn-info" wire:click="message({{ $user->id }})">
            Message
        </button>
    @else
        <p>No users available to message.</p>
    @endif
</div>