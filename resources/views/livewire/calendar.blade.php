<style>
    #calendar-container {
        position: fixed;
        top: 0;
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
</div>

@push('scripts')

<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.6.0/main.min.js'></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const Calendar = FullCalendar.Calendar;
        const calendarEl = document.getElementById('calendar');
        const calendar = new Calendar(calendarEl, {
    headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
    },
    locale: '{{ config('app.locale') }}',
    buttonText: {
                    today: 'Aujourd’hui',
                    month: 'Mois',
                    week: 'Semaine',
                    day: 'Jour',
                    list: 'Liste'
                },

    events: JSON.parse(@this.fermetures),
   

    editable: true,

    eventResize: info => @this.eventChange(info.fermetures),

    eventDrop: info => @this.eventChange(info.event),



});
        calendar.render();
    });
    function eventResize(event, delta, revertFunc) {
    var endDate = event.end.format().toString();
    var startDate = event.start.format().toString();
}
    console.log(eventResize);
</script>

<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.6.0/main.min.css' rel='stylesheet' />

@endpush