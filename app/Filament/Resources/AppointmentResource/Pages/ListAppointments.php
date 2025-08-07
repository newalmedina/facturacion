<?php

namespace App\Filament\Resources\AppointmentResource\Pages;

use App\Filament\Resources\AppointmentResource;
use Filament\Actions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

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
                        ->required(),

                    DatePicker::make('end_date')
                        ->label('Fecha fin')
                        ->required(),

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
                        ->relationship('worker', 'name')
                        ->searchable()
                        ->preload()
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
                    dd($data);
                    $template = \App\Models\AppointmentTemplate::with('slots')->findOrFail($data['template_id']);

                    $startDate = \Carbon\Carbon::parse($data['start_date']);
                    $endDate = \Carbon\Carbon::parse($data['end_date']);

                    // Asegúrate de que tengas el modelo Appointment o cambia esto por tu lógica real
                    $appointmentsCreated = 0;

                    while ($startDate->lte($endDate)) {
                        $dayOfWeek = $startDate->dayOfWeek; // 0 (domingo) a 6 (sábado)

                        foreach ($template->slots->where('day_of_week', $dayOfWeek) as $slot) {
                            \App\Models\Appointment::create([
                                'worker_id' => $template->worker_id,
                                'date' => $startDate->toDateString(),
                                'start_time' => $slot->start_time,
                                'end_time' => $slot->end_time,
                                'status' => 'pending', // puedes personalizar esto
                            ]);
                            $appointmentsCreated++;
                        }

                        $startDate->addDay();
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
