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

    #calendar.admin-calendar {
        color: white !important;
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
        const isAdmin = @this.isAdmin
        const Calendar = FullCalendar.Calendar;
        const calendarEl = document.getElementById('calendar');
        if (isAdmin) {
            calendarEl.classList.add('admin-calendar');
        }
        const calendar = new Calendar(calendarEl, {
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            buttonText: {
                today: 'Aujourd\'hui',
                month: 'Mois',
                week: 'Semaine',
                day: 'Jour',
                list: 'Liste'
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
