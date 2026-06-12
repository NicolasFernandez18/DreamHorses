<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Calendario - DreamHorses</title>
        <script>
        // On page load or when changing themes, best to add inline in `head` to avoid FOUC
        if (localStorage.getItem('theme') === 'cupcake') {
            document.documentElement.setAttribute('data-theme', 'cupcake');
        } else {
            document.documentElement.setAttribute('data-theme', 'forest');
        }
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/theme.js'])
</head>

<body>
    <div class="drawer lg:drawer-open">
    <input id="my-drawer" type="checkbox" class="drawer-toggle" />
    <div class="drawer-content bg-base-100 text-base-content">
        <!-- Botón hamburguesa -->
        <label for="my-drawer"
            class="btn btn-primary drawer-button lg:hidden m-4 shadow-md">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </label>
<x-session-alert />
<div>
    <div class="p-6 md:p-1">
            <h1 class="text-2xl sm:text-3xl font-bold text-base-content text-center sm:text-left mb-6">
                Calendario de Eventos
            </h1>
            </div>
                @if(auth()->user()?->hasAnyRole(['caretaker', 'boss', 'admin']))
                    <form action="{{ route('calendar.index') }}" method="get">
                        <button type="submit" class="btn btn-success font-bold shadow-sm">
                            lista de Eventos
                        </button>
                    </form>
                @endif
                </div>

        <div class="p-6">
            <div id='calendar'></div>
        </div>

        <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.19/index.global.min.js'></script>
        <script>
          document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
         contentHeight: 'auto',
        locale: 'es',
         buttonText: {
      today: 'Hoy',
    },
        events: @json($events),
        eventContent: function(arg) {
            let title = arg.event.title;
            let horse = arg.event.extendedProps.horse;
            let time = arg.event.extendedProps.time;

            return { html: 'Evento:' + title + '<br>' + 'Caballo: ' + horse + '<br>' + 'Hora: ' + time };
        }
    });

    calendar.render();
});
        </script>
    </div>

       <x-sidebar />
</div>
</body>

</html>
