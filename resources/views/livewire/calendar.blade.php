{{-- @if(Auth()->user()->isAdmin())
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootswatch/4.5.2/slate/bootstrap.min.css">
@endif --}}

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
        @livewire('calendar-component')
    </div>
    <div id="interventionModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        @livewire('intervention-details')
    </div>

    <div id="closureModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="px-4 pt-5 pb-4 bg-white sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left">
                            <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">Créer une fermeture</h3>
                            <div class="mt-2">
                                <input type="hidden" id="closureDate">
                                <input type="hidden" id="appartementId" value="{{$appartementId}}">

                                <label for="closureReason" class="block text-sm font-medium text-gray-700">Raison</label>
                                <input type="text" id="closureReason" class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-yellow-500 focus:border-yellow-500 sm:text-sm">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="px-4 py-3 bg-gray-50 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-yellow-400 border border-transparent rounded-md shadow-sm hover:bg-yellow-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm" onclick="saveClosure()">Enregistrer</button>
                    <button type="button" class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" onclick="closeClosureModal()">Annuler</button>
                </div>
            </div>
        </div>
    </div>

    <div id="closureModalDetails" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        @livewire('closureDetails')
    </div>

</div>


@push('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.6.0/main.min.js'></script>

<script>
    
    function openModal(type) {
        if(type == 'Reservation') {
            document.getElementById('eventModal').classList.remove('hidden');
        } else if(type == 'Closure') {
            document.getElementById('closureModalDetails').classList.remove('hidden');
        } else {
            document.getElementById('interventionModal').classList.remove('hidden');
        }
    }

    function closeModal(type) {
        if(type == 'Reservation') {
            document.getElementById('eventModal').classList.add('hidden');
        } else if (type == 'Closure'){
            document.getElementById('closureModalDetails').classList.add('hidden');
        } else {
            document.getElementById('interventionModal').classList.add('hidden');
        }
    }

    function openClosureModal(date) {
        document.getElementById('closureDate').value = date;
        document.getElementById('closureModal').classList.remove('hidden');
    }

    function closeClosureModal() {
        document.getElementById('closureModal').classList.add('hidden');
    }

    function saveClosure() {
        const date = document.getElementById('closureDate').value;
        const appartementId = document.getElementById('appartementId').value;
        const reason = document.getElementById('closureReason').value;

        if (date && reason) {
            Livewire.dispatch('createClosure', { date: date, reason: reason, appartementId: appartementId });
            closeClosureModal();
        }
    }

    function deleteClosure(reservationId) {
        Livewire.dispatch('deleteClosure', { id: reservationId });
        closeModal('Closure');
    }

    document.addEventListener('livewire:initialized', function () {
        const isAdmin = @this.isAdmin;
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
            allDayDefault: true,
            editable: !isAdmin,            
            eventOverlap: function(stillEvent, movingEvent) {
                return false;
            },

            eventAllow: function(dropInfo, draggedEvent) {
                const today = new Date().setHours(0, 0, 0, 0);
                const start = dropInfo.start.setHours(0, 0, 0, 0);
                const end = dropInfo.end ? dropInfo.end.setHours(0, 0, 0, 0) : start;
                
                if (start < today || end < today) {
                    return false;
                }
                
                return true;
            },
            
            events: [
                ...JSON.parse(@this.fermetures),
                ...JSON.parse(@this.reservations),
                ...JSON.parse(@this.interventions),
            ],

            eventClick: function(info) {
                if(info.event.extendedProps.type === 'reservation') {                    
                    Livewire.dispatch('loadReservation', { id: info.event.id });
                    openModal('Reservation');
                } else if (info.event.extendedProps.type === 'closure') {
                    Livewire.dispatch('loadClosure', { id: info.event.id });
                    openModal('Closure');
                } else {
                    Livewire.dispatch('loadIntervention', { id: info.event.extendedProps.intervention_id });
                    openModal('Intervention');
                }
            },

            dateClick: function(info) {
                const today = new Date();
                today.setHours(0, 0, 0, 0);

                const clickedDate = new Date(info.date);
                clickedDate.setHours(0, 0, 0, 0);

                if (clickedDate < today) {
                    return;
                }
                if(!isAdmin) {
                    openClosureModal(info.dateStr);
                }
            },

            eventDrop: function(info) {
                if (info.event.extendedProps.type !== 'closure') {
                    info.revert();
                    return;
                }

                const event = info.event;
                Livewire.dispatch('updateClosure', {
                    id: event.id,
                    start: event.start.toISOString().split('T')[0],
                    end: event.end ? event.end.toISOString().split('T')[0] : event.start.toISOString().split('T')[0],
                });
            },

            eventResize: function(info) {
                if (info.event.extendedProps.type !== 'closure') {
                    info.revert();
                    return;
                }

                const event = info.event;
                Livewire.dispatch('updateClosure', {
                    id: event.id,
                    start: event.start.toISOString().split('T')[0],
                    end: event.end ? event.end.toISOString().split('T')[0] : event.start.toISOString().split('T')[0],
                });
            },
        });
        calendar.render();
    });
</script>

<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.6.0/main.min.css' rel='stylesheet' />

@endpush