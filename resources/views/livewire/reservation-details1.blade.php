<div class="modal fade" id="reservationModal" tabindex="-1" role="dialog" aria-labelledby="reservationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reservationModalLabel">Détails de la Réservation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Heure de début: {{ $start_time }}</p>
                <p>Heure de fin: {{ $end_time }}</p>
                <p>Nombre de personnes: {{ $nombre_de_personne }}</p>
                <p>Prix: {{ $prix }}</p>
                <p>Statut: {{ $status }}</p>
                <p>Commentaire: {{ $commentaire }}</p>
       
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    window.addEventListener('showModal', events => {
        $('#reservationModal').modal('show');
    });
});
</script>
@endpush
