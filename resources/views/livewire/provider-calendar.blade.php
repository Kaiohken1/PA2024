<!-- Ajoutez ces liens dans la section head de votre fichier Blade -->

<style>
    #calendar-container {
        position: fixed;
        top: 6em;
        left: 0;
        right: 0;
        bottom: 0;
    }
    #calendar {
        margin: 10px auto;
        padding: 10px;
        max-width: 1100px;
        height: 700px;
    }
</style>

<div>
    <div id='calendar-container' wire:ignore>
        <div id='calendar'></div>
    </div>
    <div id="eventModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        @livewire('intervention-details')
    </div>

</div>


@push('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.6.0/main.min.js'></script>

<script>
    function openModal() {
        document.getElementById('eventModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('eventModal').classList.add('hidden');
    }

    document.addEventListener('livewire:initialized', function () {
        const Calendar = FullCalendar.Calendar;
        const calendarEl = document.getElementById('calendar');
        const calendar = new Calendar(calendarEl, {
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            locale: '{{ config('app.locale') }}',
            events: [
                ...JSON.parse(@this.interventions),
                ...JSON.parse(@this.absences),
            ],
            eventClick: function(info) {
                if(info.event.extendedProps.intervention_id) {
                    Livewire.dispatch('loadIntervention', { id: info.event.extendedProps.intervention_id });
                    openModal();
                }
            }
        });
        calendar.render();
    });
</script>

<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.6.0/main.min.css' rel='stylesheet' />

@endpush
