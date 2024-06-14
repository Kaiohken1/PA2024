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


<div>
    <div id='calendar-container' wire:ignore>
        <div id='calendar'></div>
        <div id="reservation-details">
            <h2>Reservation Details</h2>
            <p id="reservationName"></p>
            <p id="reservationSummary"></p>
        </div>
    </div>
</div>

@push('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.6.0/main.min.js'></script>
<script>
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

    // Function to check for date conflicts
    function isDateConflict(newStart, newEnd, events) {
        for (let event of events) {
            let existingStart = new Date(event.start);
            let existingEnd = new Date(event.end);

            if (
                (newStart <= existingEnd && newStart >= existingStart) ||
                (newEnd <= existingEnd && newEnd >= existingStart) ||
                (newStart <= existingStart && newEnd >= existingEnd)
            ) {
                return true;
            }
        }
        return false;
    }

    // Function to format dates
    function formatDate(date) {
        return date.toISOString().slice(0, 19).replace('T', ' ');
    }

    document.addEventListener('DOMContentLoaded', function () {
        const Calendar = FullCalendar.Calendar;
        const calendarEl = document.getElementById('calendar');
        const fermetures = @json($fermetures);
        const reservations = @json($reservations);

        // Mapping events to FullCalendar format
        const events = fermetures.map(event => ({
            id: event.id,
            title: 'Fermeture',
            start: event.start,
            end: event.end ? new Date(new Date(event.end).getTime() + 86400000).toISOString().split('T')[0] : null,
            allDay: true,
            className: 'fc-closure'
        }));

        reservations.forEach(event => {
            events.push({
                id: create_UUID(),
                title: 'Reservation',
                start: event.start_time,
                end: event.end_time ? new Date(new Date(event.end_time).getTime() + 86400000).toISOString().split('T')[0] : null,
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
            editable: true,
            selectable: true,

            // Event click handler to show reservation details
            eventClick: function(info) {
                const eventObj = info.event;
                if (eventObj.extendedProps.name && eventObj.extendedProps.surname && eventObj.extendedProps.summary) {
                    document.getElementById('reservationName').innerText = "Name: " + eventObj.extendedProps.name + " " + eventObj.extendedProps.surname;
                    document.getElementById('reservationSummary').innerText = "Summary: " + eventObj.extendedProps.summary;
                }
            },

            // Event resize handler
            eventResize: function(info) {
                const eventObj = info.event;
                @this.eventChange({
                    id: eventObj.id,
                    start: formatDate(eventObj.start),
                    end: eventObj.end ? formatDate(eventObj.end) : null
                });
            },

            // Event drop handler
            eventDrop: function(info) {
                const eventObj = info.event;
                @this.eventChange({
                    id: eventObj.id,
                    start: formatDate(eventObj.start),
                    end: eventObj.end ? formatDate(eventObj.end) : null
                });
            },

            // Event select handler
            select: function(arg) {
                const title = prompt('Titre :');
                const id = create_UUID();

                const newStart = new Date(arg.start);
                const newEnd = new Date(arg.end);

                if (isDateConflict(newStart, newEnd, reservations)) {
                    alert('La fermeture chevauche une réservation existante.');
                    calendar.unselect();
                    return;
                }

                if (title) {
                    calendar.addEvent({
                        id: id,
                        title: title,
                        start: newStart.toISOString(),
                        end: newEnd ? new Date(newEnd.getTime() + 86400000).toISOString().split('T')[0] : null,
                        allDay: arg.allDay
                    });
                    @this.eventAdd({
                        id: id,
                        title: title,
                        start: newStart.toISOString(),
                        end: newEnd ? new Date(newEnd.getTime() + 86400000).toISOString().split('T')[0] : null,
                        allDay: true
                    });
                }
                calendar.unselect();
            }
        });

        calendar.render();
    });

    // Form submission handler
    document.getElementById('editForm').addEventListener('submit', function(event) {
        event.preventDefault();
        const eventId = document.getElementById('eventId').value;
        const start = formatDate(new Date(document.getElementById('start').value));
        const end = formatDate(new Date(document.getElementById('end').value));

        fetch('/update-event', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                id: eventId,
                start: start,
                end: end,
                allDay: true
            })
        })
        .then(response => response.json())
        .then(data => {
            const event = calendar.getEventById(eventId);
            event.setStart(start);
            event.setEnd(end ? new Date(new Date(end).getTime() + 86400000).toISOString().split('T')[0] : null);
            event.setAllDay(true);
            document.getElementById('detail-panel').style.display = 'none';
        })
        .catch(error => console.error('Error:', error));
    });
</script>

<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.6.0/main.min.css' rel='stylesheet' />
@endpush
