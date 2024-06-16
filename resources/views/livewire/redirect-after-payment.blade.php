<div class="flex justify-center items-center h-screen">
    <div class="text-center">
        <div class="flex justify-center mb-4">
            <div class="spinner-border animate-spin inline-block w-8 h-8 border-4 rounded-full" role="status">
                <span class="visually-hidden">Chargement...</span> 
                <span class="loading loading-spinner loading-lg"></span>
            </div>
        </div>
        <p class="text-lg">Redirection en cours, veuillez patienter...</p>
    </div>
</div>

<script>
    document.addEventListener('livewire:load', function () {
        @this.redirectToPlan();
    });
</script>
