<div>
    <style>
        #calendar-container {
            position: relative;
            display: flex;
            margin: 10px;
            padding: 10px;
            border: 1px solid #ccc;
            background-color: #fff;
        }
        #calendar {
            flex: 70%;
            margin-right: 10px;
            padding: 10px;
            border-right: 1px solid #ccc;
            height: 700px;
        }
        #reservation-details {
            flex: 30%;
            margin-left: 10px;
            padding: 20px;
            border: 1px solid #ccc;
            background-color: #f9f9f9;
        }
        .fc-reservation {
            background-color: green !important;
            color: white !important;
        }
    </style>

    <div id='calendar-container' wire:ignore>
        <div id='calendar'></div>
        <div id="reservation-details">
            <h2>Reservation Details</h2>
            <p id="reservationName"></p>
            <p id="reservationSummary"></p>
        </div>
    </div>
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createClosureModal">
        Créer une fermeture
    </button>

    <!-- Modal for creating closure -->
    <div class="modal fade" id="createClosureModal" tabindex="-1" role="dialog" aria-labelledby="createClosureModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createClosureModalLabel">Créer une fermeture</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form wire:submit.prevent="createClosure">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="closureTitle">Titre</label>
                            <input type="text" class="form-control" id="closureTitle" wire:model="closureTitle" required>
                        </div>
                        <div class="form-group">
                            <label for="closureStart">Date de début</label>
                            <input type="date" class="form-control" id="closureStart" wire:model="closureStart" required>
                        </div>
                        <div class="form-group">
                            <label for="closureDays">Nombre de jours</label>
                            <input type="number" class="form-control" id="closureDays" wire:model="closureDays" min="1" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn btn-primary">Créer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.6.0/main.min.js'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const Calendar = FullCalendar.Calendar;
            const calendarEl = document.getElementById('calendar');
            const fermetures = @json($fermetures);
            const reservations = @json($reservations);

            // Function to create a unique identifier
            function create_UUID() {
                let dt = new Date().getTime();
                const uuid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, c => {
                    let r = (dt + Math.random() * 16) % 16 | 0;
                    dt = Math.floor(dt / 16);
                    return (c == 'x' ? r : (r & 0x3 | 0x8)).toString(16);
                });
                return uuid;
            }

            // Function to add one day to a date
            function addOneDay(date) {
                let newDate = new Date(date);
                newDate.setDate(newDate.getDate() + 1);
                return newDate;
            }

            // Mapping events to FullCalendar format
            const events = fermetures.map(event => ({
                id: event.id,
                title: 'Fermeture',
                start: event.start,
                end: event.end ? addOneDay(new Date(event.end)).toISOString() : null,
                allDay: true,
                className: 'fc-closure'
            }));

            reservations.forEach(event => {
                events.push({
                    id: create_UUID(),
                    title: 'Reservation',
                    start: event.start_time,
                    end: event.end_time ? addOneDay(new Date(event.end_time)).toISOString() : null,
                    allDay: true,
                    className: 'fc-reservation',
                    extendedProps: {
                        name: event.user.name,
                        surname: event.user.surname,
                        summary: event.summary 
                    }
                });
            });

            // Initializing FullCalendar
            const calendar = new Calendar(calendarEl, {
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                },
                locale: '{{ config('app.locale') }}',
                timeZone: 'local', // Ensure the timezone is set correctly
                buttonText: {
                    today: 'Aujourd’hui',
                    month: 'Mois',
                    week: 'Semaine',
                    day: 'Jour',
                    list: 'Liste'
                },
                events: events,
                editable: false,
                selectable: true,

                // Event click handler to show reservation details
                eventClick: function(info) {
                    const eventObj = info.event;
                    if (eventObj.extendedProps.name && eventObj.extendedProps.surname && eventObj.extendedProps.summary) {
                        document.getElementById('reservationName').innerText = "Name: " + eventObj.extendedProps.name + " " + eventObj.extendedProps.surname;
                        document.getElementById('reservationSummary').innerText = "Summary: " + eventObj.extendedProps.summary;
                    }
                }
            });

            calendar.render();

            window.livewire.on('closureAdded', () => {
                // Reload the calendar events
                calendar.refetchEvents();
            });

            window.addEventListener('close-modal', event => {
                $('#createClosureModal').modal('hide');
            });
        });
    </script>
    @endpush
</div>
