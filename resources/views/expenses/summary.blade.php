<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Resumen de Gastos - DreamHorses</title>
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
            <h2 class="text-3xl font-bold text-base-content mb-4"> Resumen de Gastos</h2>

            <x-session-alert />

            <div class="mb-4">
                <form action="{{ route('expenses.summary') }}" method="GET">
                    <div class="flex flex-col md:flex-row gap-2 justify-start items-end">
                        <div class="form-control">
                            <label for="from_month" class="label">
                                <span class="label-text font-semibold">Desde</span>
                            </label>
                            <select name="from_month" id="from_month" class="select select-bordered w-full max-w-xs">
                                @foreach ($availableMonths as $value => $label)
                                    <option value="{{ $value }}"
                                        {{ request('from_month') == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-control">
                            <label for="to_month" class="label">
                                <span class="label-text font-semibold">Hasta</span>
                            </label>
                            <select name="to_month" id="to_month" class="select select-bordered w-full max-w-xs">
                                @foreach ($availableMonths as $value => $label)
                                    <option value="{{ $value }}"
                                        {{ request('to_month') == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Buscar</button>
                    </div>
                </form>
            </div>

            @php
                $filtradoPorFecha = request()->filled('from_month') || request()->filled('to_month');
            @endphp

            @if ($filtradoPorFecha)
                <div class="flex justify-between items-center mt-6 mb-2">
                    <h2 class="text-lg font-semibold">Resumen mensual por categoría</h2>
                    <div class="flex items-center gap-2">
                        <form id="pdfForm" action="{{ route('expenses.summary.pdf') }}" method="POST" class="m-0">
                            @csrf
                            <input type="hidden" name="from_month" value="{{ request('from_month') }}">
                            <input type="hidden" name="to_month" value="{{ request('to_month') }}">
                            <input type="hidden" name="chart_image" id="chartImageInput">
                            <button type="button" onclick="generatePdfWithChart()" class="btn btn-secondary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                <span id="btnText">Descargar PDF</span>
                            </button>
                        </form>
                        <a href="{{ route('expenses.chart', ['start_month' => request('from_month'), 'end_month' => request('to_month')]) }}"
                            class="btn btn-info">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                            </svg>
                            Ver Gráfico
                        </a>
                    </div>
                </div>

                @if ($monthlyCategorySummary->isNotEmpty())
                    <!-- Canvas oculto para generar el gráfico -->
                    <div style="position: absolute; left: -9999px;">
                        <canvas id="hiddenPieChart" width="800" height="600"></canvas>
                    </div>

                    <div class="overflow-x-auto mb-6">
                        <table class="table table-zebra w-full">
                            <thead>
                                <tr>
                                    <th class="p-4">Categoría</th>
                                    <th class="p-4 text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($monthlyCategorySummary as $item)
                                    <tr>
                                        <td class="p-4">{{ $item->category }}</td>
                                        <td class="p-4 text-success font-semibold text-end ">
                                            ${{ number_format($item->total_amount, 2) }}</td>
                                    </tr>
                                @endforeach
                                {{-- Fila de total general --}}
                                <tr class="font-bold border-t-2 border-base-300">
                                    <td class="p-4">Total general</td>
                                    <td class="p-4 text-success font-semibold text-end ">
                                        ${{ number_format($totalGeneral, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="mt-4 text-gray-500">No hay datos para el período o búsqueda seleccionados.</p>
                @endif
            @endif

            @can('expenses.create')
                <div class="flex justify-end mb-4">
                    <a href="{{ route('expenses.create') }}" class="btn btn-success font-bold">
                        Nuevo Gasto
                    </a>
                </div>
            @endcan

        </div>
    </div>

    <x-sidebar />
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let pieChartInstance = null;

    document.addEventListener('DOMContentLoaded', function() {
        @if ($filtradoPorFecha && $monthlyCategorySummary->isNotEmpty())
            generateHiddenChart();
        @endif
    });

    function generateHiddenChart() {
        const canvas = document.getElementById('hiddenPieChart');
        if (!canvas) return;

        const ctx = canvas.getContext('2d');

        const categoryLabels = @json($monthlyCategorySummary->pluck('category'));
        const categoryData = @json($monthlyCategorySummary->pluck('total_amount'));

        if (pieChartInstance) {
            pieChartInstance.destroy();
        }

        pieChartInstance = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: categoryLabels,
                datasets: [{
                    label: 'Gastos por Categoría',
                    data: categoryData,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.8)',
                        'rgba(54, 162, 235, 0.8)',
                        'rgba(255, 206, 86, 0.8)',
                        'rgba(75, 192, 192, 0.8)',
                        'rgba(153, 102, 255, 0.8)',
                        'rgba(255, 159, 64, 0.8)',
                        'rgba(199, 199, 199, 0.8)',
                        'rgba(83, 102, 255, 0.8)',
                        'rgba(255, 99, 255, 0.8)',
                        'rgba(50, 205, 50, 0.8)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(199, 199, 199, 1)',
                        'rgba(83, 102, 255, 1)',
                        'rgba(255, 99, 255, 1)',
                        'rgba(50, 205, 50, 1)'
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            font: {
                                size: 14
                            },
                            padding: 15
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed !== null) {
                                    label += '$' + context.parsed.toFixed(2);
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });
    }

    function generatePdfWithChart() {
        const canvas = document.getElementById('hiddenPieChart');
        const button = document.querySelector('#pdfForm button');
        const btnText = document.getElementById('btnText');

        if (canvas && pieChartInstance) {
            // Deshabilitar botón y mostrar estado de carga
            button.disabled = true;
            btnText.textContent = 'Generando PDF...';

            // Esperar a que el gráfico se renderice completamente
            setTimeout(() => {
                try {
                    // Capturar el canvas como imagen en formato PNG
                    const imageData = canvas.toDataURL('image/png', 0.8);
                    document.getElementById('chartImageInput').value = imageData;
                    document.getElementById('pdfForm').submit();

                    // Restaurar botón después de un momento
                    setTimeout(() => {
                        button.disabled = false;
                        btnText.textContent = 'Descargar PDF';
                    }, 2000);
                } catch (error) {
                    console.error('Error al generar imagen:', error);
                    alert('Error al generar el gráfico. Por favor, intenta nuevamente.');
                    button.disabled = false;
                    btnText.textContent = 'Descargar PDF';
                }
            }, 500);
        } else {
            // Si no hay gráfico, enviar el formulario directamente
            document.getElementById('pdfForm').submit();
        }
    }
</script>
</body>

</html>
