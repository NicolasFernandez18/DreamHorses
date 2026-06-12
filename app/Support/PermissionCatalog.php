<?php

namespace App\Support;

class PermissionCatalog
{
    public static function sections(): array
    {
        return [
            'Caballos' => [
                'horses.view' => 'Ver caballos',
                'horses.create' => 'Crear caballos',
                'horses.edit' => 'Editar caballos',
                'horses.delete' => 'Eliminar caballos',
                'horses.pdf' => 'Descargar PDF de caballos',
            ],
            'Carreras' => [
                'race.view' => 'Ver carreras',
                'race.create' => 'Crear carreras',
                'race.edit' => 'Editar carreras',
                'race.delete' => 'Eliminar carreras',
            ],
            'Entrenamientos' => [
                'training.view' => 'Ver entrenamientos',
                'training.create' => 'Crear entrenamientos',
                'training.edit' => 'Editar entrenamientos',
                'training.delete' => 'Eliminar entrenamientos',
            ],
            'Veterinario' => [
                'vet-visits.view' => 'Ver visitas veterinarias',
                'vet-visits.create' => 'Crear visitas veterinarias',
                'vet-visits.edit' => 'Editar visitas veterinarias',
                'vet-visits.delete' => 'Eliminar visitas veterinarias',
            ],
            'Herrería' => [
                'blacksmiths.view' => 'Ver herrados',
                'blacksmiths.create' => 'Crear herrados',
                'blacksmiths.edit' => 'Editar herrados',
                'blacksmiths.delete' => 'Eliminar herrados',
            ],
            'Calendario' => [
                'calendar.view' => 'Ver eventos',
                'calendar.create' => 'Crear eventos',
                'calendar.edit' => 'Editar eventos',
                'calendar.delete' => 'Eliminar eventos',
                'calendar.schedule' => 'Ver calendario',
            ],
            'Gastos' => [
                'expenses.view' => 'Ver gastos',
                'expenses.create' => 'Crear gastos',
                'expenses.edit' => 'Editar gastos',
                'expenses.delete' => 'Eliminar gastos',
                'expenses.chart' => 'Ver gráfico de gastos',
                'expenses.summary' => 'Ver resumen de gastos',
            ],
            'Cuidadores' => [
                'caretakers.view' => 'Ver cuidadores',
                'caretakers.show' => 'Ver detalle de cuidadores',
                'caretakers.delete' => 'Eliminar cuidadores',
                'caretakers.reassign' => 'Reasignar cuidadores',
            ],
            'Roles' => [
                'roles.manage' => 'Administrar roles',
            ],
            'Studs' => [
                'studs.view' => 'Ver studs',
                'studs.create' => 'Crear studs',
                'studs.edit' => 'Editar studs',
                'studs.delete' => 'Eliminar studs',
                'studs.join' => 'Unirse a studs',
                'studs.leave' => 'Renunciar a studs',
                'studs.kick' => 'Despedir cuidadores del stud',
                'studs.hire' => 'Solicitar contrato del stud',
                'studs.fire' => 'Cancelar contrato del stud',
                'studs.respond' => 'Responder solicitudes de contrato',
            ],
        ];
    }

    public static function all(): array
    {
        return collect(self::sections())
            ->flatMap(fn (array $permissions) => $permissions)
            ->all();
    }

    public static function rolePermissions(): array
    {
        return [
            'admin' => array_keys(self::all()),
            'boss' => [
                'horses.view',
                'horses.create',
                'horses.edit',
                'horses.pdf',
                'race.view',
                'training.view',
                'vet-visits.view',
                'blacksmiths.view',
                'calendar.view',
                'calendar.schedule',
                'expenses.view',
                'expenses.chart',
                'expenses.summary',
                'caretakers.view',
                'caretakers.show',
                'caretakers.delete',
                'caretakers.reassign',
                'studs.view',
                'studs.hire',
                'studs.fire',
                'studs.respond',
            ],
            'caretaker' => [
                'horses.view',
                'horses.edit',
                'horses.pdf',
                'race.view',
                'race.create',
                'race.edit',
                'race.delete',
                'training.view',
                'training.create',
                'training.edit',
                'training.delete',
                'vet-visits.view',
                'vet-visits.create',
                'vet-visits.edit',
                'vet-visits.delete',
                'blacksmiths.view',
                'blacksmiths.create',
                'blacksmiths.edit',
                'blacksmiths.delete',
                'calendar.view',
                'calendar.create',
                'calendar.edit',
                'calendar.delete',
                'calendar.schedule',
                'expenses.view',
                'expenses.create',
                'expenses.edit',
                'expenses.delete',
                'studs.view',
                'studs.create',
                'studs.edit',
                'studs.delete',
                'studs.join',
                'studs.leave',
                'studs.kick',
            ],
        ];
    }
}
