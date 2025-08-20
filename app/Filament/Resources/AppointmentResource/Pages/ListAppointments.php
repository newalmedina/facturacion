<?php

namespace App\Filament\Resources\AppointmentResource\Pages;

use App\Filament\Resources\AppointmentResource;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Str;

class ListAppointments extends ListRecords
{
    protected static string $resource = AppointmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('generateFromTemplate')
                ->label('Generar cita desde plantilla')
                //->icon('heroicon-o-calendar-plus')
                ->color('warning')
                ->form([
                    DatePicker::make('start_date')
                        ->label('Fecha inicio')
                        ->required()
                        ->minDate(Carbon::today()), // no menor que hoy

                    DatePicker::make('end_date')
                        ->label('Fecha fin')
                        ->required()
                        ->minDate(fn(callable $get) => $get('start_date') ?? Carbon::today()), // no menor que start_date, o hoy si no hay start_date

                    Select::make('template_id')
                        ->label('Plantilla')
                        ->options(
                            \App\Models\AppointmentTemplate::where('active', true)
                                ->pluck('name', 'id')
                        )
                        ->searchable()
                        ->preload()
                        ->required(),
                    Select::make('worker_id')
                        ->label('Empleado')
                        ->relationship('worker', 'name', fn($query) => $query->canAppointment()) // filtra solo empleados disponibles
                        ->searchable()
                        ->preload()
                        ->required()

                        ->placeholder('Selecciona empleado'),
                    Toggle::make('active')
                        ->label('Activar')
                        ->inline(false)
                        ->default(true),

                ])
                ->modalHeading('Generar citas desde plantilla')
                ->modalSubmitActionLabel('Generar')
                ->modalWidth('md')
                ->action(function (array $data) {
                    $template = \App\Models\AppointmentTemplate::with('slots')->findOrFail($data['template_id']);

                    $startDate = Carbon::parse($data['start_date']);
                    $endDate = Carbon::parse($data['end_date']);
                    $workerId = $data['worker_id'];
                    $active = $data['active'] ?? true;

                    $appointmentsCreated = 0;

                    for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
                        $dayOfWeek = strtolower($date->format('l')); // monday, tuesday, ...

                        // Filtrar slots que correspondan al día de la semana actual
                        $daySlots = $template->slots->where('day_of_week', $dayOfWeek);

                        foreach ($daySlots as $slot) {
                            // Verificar solapamiento
                            $overlap = \App\Models\Appointment::where('worker_id', $workerId)
                                ->where('date', $date->format('Y-m-d'))
                                ->where(function ($query) use ($slot) {
                                    $query->where('start_time', '<', $slot->end_time)
                                        ->where('end_time', '>', $slot->start_time);
                                })
                                ->exists();

                            if (!$overlap) {
                                \App\Models\Appointment::create([
                                    'worker_id' => $workerId,
                                    'template_id' => $template->id,
                                    'date' => $date->format('Y-m-d'),
                                    'start_time' => $slot->start_time,
                                    'end_time' => $slot->end_time,
                                    'active' => $active,
                                    'slug' => (string) Str::uuid(),
                                ]);

                                $appointmentsCreated++;
                            }
                        }
                    }

                    Notification::make()
                        ->title("Citas generadas: $appointmentsCreated")
                        ->success()
                        ->send();
                }),


            Actions\CreateAction::make(),

        ];
    }
}
