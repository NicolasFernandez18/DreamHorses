<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Gastos - DreamHorses</title>
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
            <label for="my-drawer" class="btn btn-primary drawer-button lg:hidden m-4 shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </label>

            <!-- Contenido principal -->
            <div class="p-6 md:p-8 max-w-6xl mx-auto space-y-6">
                <h2 class="text-3xl font-bold text-base-content mb-4"> Lista de Gastos</h2>

                <x-session-alert />

                <!-- Buscador -->
                <div class="mb-2">
                    <form action="{{ route('expenses.index') }}" method="GET">
                        <div class="flex">
                            <input type="text" name="search" placeholder="Buscar por caballo..."
                                class="input input-bordered w-full max-w-xs mr-2" value="{{ request('search') }}" />
                            <button type="submit" class="btn btn-primary">Buscar</button>
                        </div>
                    </form>
                </div>
                <div class="mb-2">
                    <form action="{{ route('expenses.index') }}" method="GET">
                        <div class="flex flex-col md:flex-row gap-2 justify-start items-end">
                            <div class="form-control">
                                <label for="from_date" class="label">
                                    <span class="label-text font-semibold">Desde</span>
                                </label>
                                <input type="date" name="from_date" value="{{ request('from_date') }}"
                                    class="input input-bordered" />
                            </div>
                            <div class="form-control">
                                <label for="to_date" class="label">
                                    <span class="label-text font-semibold">Hasta</span>
                                </label>
                                <input type="date" name="to_date" value="{{ request('to_date') }}"
                                    class="input input-bordered" />
                            </div>
                            <button type="submit" class="btn btn-primary">Buscar</button>
                        </div>
                    </form>
                </div>
                <div class="flex gap-4 mb-4 justify-end">
                    <div class="flex">
                        <form action="{{ route('expenses.pdf') }}" method="GET" target="_blank">
                            <input type="hidden" name="search" value="{{ request('search') }}">
                            <input type="hidden" name="from_date" value="{{ request('from_date') }}">
                            <input type="hidden" name="to_date" value="{{ request('to_date') }}">
                            <button type="submit" class="btn btn-outline btn-primary">
                                Descargar PDF
                            </button>
                        </form>
                    </div>
                    @can('expenses.create')
                        <div class="flex">
                            <a href="{{ route('expenses.create') }}" class="btn btn-success font-bold">
                                Nuevo Gasto
                            </a>
                        </div>
                    @endcan
                    <div class="p-6 md:p-2">
                        @if ($horseId)
                            <a href="{{ route('horses.show', $horseId) }}" class="btn btn-sm btn-secondary mb-4">⬅
                                Volver al
                                caballo</a>
                        @endif
                    </div>
                </div>
                <div class="overflow-x-auto bg-base-200 rounded-lg shadow-lg">
                    <table id="expenses-table" class="table-auto w-full text-sm text-left text-base-content">
                        <thead class="bg-base-300 text-base-content/80 uppercase">
                            <tr>
                                @php
                                    $columns = [
                                        'date' => ['name' => 'Fecha', 'type' => 'date'],
                                        'horse' => ['name' => 'Caballo', 'type' => 'string'],
                                        'category' => ['name' => 'Categoría', 'type' => 'string'],
                                        'description' => ['name' => 'Descripción', 'type' => 'string'],
                                        'amount' => ['name' => 'Monto', 'type' => 'number'],
                                    ];
                                    $i = 0;
                                @endphp
                                @foreach ($columns as $columnKey => $columnData)
                                    <th class="p-4 cursor-pointer sortable-header"
                                        data-column-index="{{ $i }}" data-column-name="{{ $columnKey }}"
                                        data-column-type="{{ $columnData['type'] }}">
                                        <div class="flex items-center gap-1">
                                            {{ $columnData['name'] }}
                                            <span class="sort-icon"></span>
                                        </div>
                                    </th>
                                    @php $i++; @endphp
                                @endforeach

                                @canany(['expenses.edit', 'expenses.delete'])
                                    <th class="p-4">Acciones</th>
                                @endcanany
                            </tr>
                        </thead>
                        <tbody id="expenses-tbody">
                            @forelse ($expenses as $expense)
                                <tr class="border-b border-base-300 hover:bg-base-300">
                                    <td class="p-4">{{ \Carbon\Carbon::parse($expense->date)->format('d/m/Y') }}
                                    </td>
                                    <td class="p-4">{{ $expense->horse->name }}</td>
                                    <td class="p-4">{{ $expense->category }}</td>
                                    <td class="p-4">{{ $expense->description }}</td>
                                    <td class="p-4 text-success font-semibold text-end ">
                                        ${{ number_format($expense->amount, 2) }}</td>
                                    @canany(['expenses.edit', 'expenses.delete'])
                                        <td class="p-4 flex gap-2">
                                            @can('expenses.edit')
                                            <a href="{{ route('expenses.edit', $expense->id) }}"
                                                class="btn btn-xs btn-warning">Editar</a>
                                            @endcan
                                            @can('expenses.delete')
                                                <div>
                                                    <button class="btn btn-xs btn-error"
                                                        onclick="document.getElementById('modal_expense_{{ $expense->id }}').showModal()">Eliminar</button>
                                                    <x-delete-modal :id="'modal_expense_' . $expense->id" :action="route('expenses.destroy', $expense->id)" />
                                                </div>
                                            @endcan
                                        </td>
                                    @endcanany
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-base-content/70 p-4">No hay gastos
                                        registrados.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <x-sidebar />
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const table = document.getElementById('expenses-table');
            if (!table) return;

            const headers = table.querySelectorAll('thead th.sortable-header');
            const tbody = table.querySelector('tbody');
            // The initial sort is done by Laravel ('date', 'desc').
            // We set the state to reflect this and show the correct icon.
            let currentSort = {
                columnIndex: 0,
                sortOrder: 'desc'
            };

            const ascIcon =
                `<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" /></svg>`;
            const descIcon =
                `<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>`;

            // Set initial sort icon
            updateSortIcons();

            headers.forEach(header => {
                header.addEventListener('click', () => {
                    const columnIndex = parseInt(header.dataset.columnIndex);
                    const columnType = header.dataset.columnType;

                    let sortOrder;
                    if (currentSort.columnIndex === columnIndex) {
                        sortOrder = currentSort.sortOrder === 'asc' ? 'desc' : 'asc';
                    } else {
                        sortOrder = 'asc';
                    }

                    currentSort = {
                        columnIndex,
                        sortOrder
                    };

                    updateSortIcons();
                    sortTable(columnIndex, columnType, sortOrder);
                });
            });

            function updateSortIcons() {
                headers.forEach(header => {
                    const iconContainer = header.querySelector('.sort-icon');
                    if (iconContainer && parseInt(header.dataset.columnIndex) === currentSort.columnIndex) {
                        iconContainer.innerHTML = currentSort.sortOrder === 'asc' ? ascIcon : descIcon;
                    } else if (iconContainer) {
                        iconContainer.innerHTML = '';
                    }
                });
            }

            function sortTable(columnIndex, columnType, sortOrder) {
                const rows = Array.from(tbody.querySelectorAll('tr'));

                const emptyRow = tbody.querySelector('td[colspan="6"]');
                if (emptyRow) return;

                rows.sort((a, b) => {
                    let valA = a.cells[columnIndex].textContent.trim();
                    let valB = b.cells[columnIndex].textContent.trim();

                    switch (columnType) {
                        case 'number':
                            // For currency like "$1,234.56"
                            valA = parseFloat(valA.replace(/[$,]/g, ''));
                            valB = parseFloat(valB.replace(/[$,]/g, ''));
                            break;
                        case 'date':
                            // For dates like "dd/mm/YYYY"
                            const partsA = valA.split('/');
                            valA = new Date(partsA[2], partsA[1] - 1, partsA[0]);
                            const partsB = valB.split('/');
                            valB = new Date(partsB[2], partsB[1] - 1, partsB[0]);
                            break;
                        case 'string':
                        default:
                            // case-insensitive string comparison
                            valA = valA.toLowerCase();
                            valB = valB.toLowerCase();
                            break;
                    }

                    if (valA < valB) {
                        return sortOrder === 'asc' ? -1 : 1;
                    }
                    if (valA > valB) {
                        return sortOrder === 'asc' ? 1 : -1;
                    }
                    return 0;
                });

                // Re-append sorted rows
                rows.forEach(row => tbody.appendChild(row));
            }
        });
    </script>
</body>

</html>
