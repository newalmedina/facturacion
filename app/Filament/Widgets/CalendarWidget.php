<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\AppointmentResource;
use App\Mail\AppointmentChangeStatusMail;
use App\Mail\AppointmentChangeStatusToWorkerMail;
use App\Models\Appointment;
use Carbon\Carbon;
use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;
use Filament\Forms\Components\Button;
use Illuminate\Support\Facades\Auth;
use Saade\FilamentFullCalendar\Actions;

class CalendarWidget extends FullCalendarWidget
{
    public Model|string|null $model = Appointment::class;

    public ?string $selectedStatus = null;

    public ?int $selectedWorker = null; // almacenar el user_id seleccionado


    public function fetchEvents(array $fetchInfo): array
    {
        $fechaInicio = Carbon::parse($fetchInfo['start'])->format("Y-m-d");
        $fechaFin = Carbon::parse($fetchInfo['end'])->format("Y-m-d");

        $query = Appointment::whereBetween('date', [$fechaInicio, $fechaFin]);

        // Filtrar por estado
        if (!empty($this->selectedStatus)) {
            $query->where('status', $this->selectedStatus);
        }
        if ($this->isAdminPanel()) {
            // Filtrar por trabajador
            if (!empty($this->selectedWorker)) {
                $query->where('worker_id', $this->selectedWorker);
            }
        } else {
            $query->where('worker_id', Auth::user()->id);
        }


        return $query->get()->map(function (Appointment $appointment) {
            $title = $appointment->worker->name;
            if ($appointment->requester_name) {
                $title .= " - " . $appointment->requester_name;
            }
            if ($appointment->item?->name) {
                $title .= " | " . $appointment->item->name;
            }
            return [
                'id'          => $appointment->id,
                'title'       => $title,
                'start'       => $appointment->start_date?->toDateTimeString(),
                'end'         => $appointment->end_date?->toDateTimeString(),
                'status'      => $appointment->status,
                'allDay'      => false,
                'color'       => $appointment->status_color,
                'textColor'   => '#ffffff',
                'borderColor' => $appointment->status_color,
                'itemName'    => $appointment->item?->name,
            ];
        })->all();
    }


    public function getFormSchema(): array
    {
        // Campos adicionales al principio: botones de acción
        // $extraFields = [
        //     Forms\Components\Toggle::make('confirmNotification')
        //         ->label('Confirmar solicitud')
        //         ->icon('heroicon-m-check-circle')
        //         ->color('success')
        //         ->tooltip('Confirmar la solicitud y/o enviar notificación')
        //         ->requiresConfirmation()
        //         ->modalHeading('Confirmar solicitud')
        //         ->modalSubmitActionLabel('Confirmar')
        //         ->modalCancelActionLabel('Regresar')
        //         ->form([
        //             \Filament\Forms\Components\Checkbox::make('send_notification')
        //                 ->label('Enviar notificación por correo')
        //                 ->default(false),
        //         ])
        //         ->action(function ($record, array $data) {
        //             $record->status = 'confirmed';
        //             $record->save();

        //             if (!empty($data['send_notification']) && $record->requester_email) {
        //                 Mail::to($record->requester_email)
        //                     ->send(new AppointmentChangeStatusMail($record));
        //                 if ($record->worker && $record->worker->email) {
        //                     Mail::to($record->worker->email)
        //                         ->send(new AppointmentChangeStatusToWorkerMail($record));
        //                 }

        //                 $record->notification_sended = true;
        //                 $record->save();

        //                 Notification::make()
        //                     ->title('Solicitud confirmada y notificación enviada')
        //                     ->success()
        //                     ->send();
        //             } else {
        //                 Notification::make()
        //                     ->title('Solicitud confirmada')
        //                     ->success()
        //                     ->send();
        //             }
        //         }),

        //     Forms\Components\Toggle::make('cancelNotification')
        //         ->label('Cancelar solicitud')
        //         ->icon('heroicon-m-x-circle')
        //         ->color('danger')
        //         ->tooltip('Cancelar la solicitud y/o enviar notificación')
        //         ->requiresConfirmation()
        //         ->modalHeading('Cancelar solicitud')
        //         ->modalSubmitActionLabel('Cancelar')
        //         ->modalCancelActionLabel('Regresar')
        //         ->form([
        //             \Filament\Forms\Components\Checkbox::make('send_notification')
        //                 ->label('Enviar notificación por correo')
        //                 ->default(false),
        //         ])
        //         ->action(function ($record, array $data) {
        //             $record->status = 'cancelled';
        //             $record->save();

        //             if (!empty($data['send_notification']) && $record->requester_email) {
        //                 Mail::to($record->requester_email)
        //                     ->send(new AppointmentChangeStatusMail($record));
        //                 if ($record->worker && $record->worker->email) {
        //                     Mail::to($record->worker->email)
        //                         ->send(new AppointmentChangeStatusToWorkerMail($record));
        //                 }

        //                 $record->notification_sended = true;
        //                 $record->save();

        //                 Notification::make()
        //                     ->title('Solicitud cancelada y notificación enviada')
        //                     ->warning()
        //                     ->send();
        //             } else {
        //                 Notification::make()
        //                     ->title('Solicitud cancelada')
        //                     ->warning()
        //                     ->send();
        //             }
        //         }),
        // ];

        $extraFields = [];
        // Tomamos el schema original de AppointmentResource
        $originalFields = AppointmentResource::getFormSchema();

        // Combinamos los arrays: los extraFields irán primero
        return array_merge($extraFields, $originalFields);
    }


    // 👇 Nuevo método helper
    private function isAdminPanel(): bool
    {
        return Filament::getCurrentPanel()?->getId() === 'admin';
    }

    protected function headerActions(): array
    {
        return $this->isAdminPanel()
            ? [Actions\CreateAction::make()->label("Nueva cita")]
            : [];
    }

    protected function modalActions(): array
    {
        if (! $this->isAdminPanel()) {
            return [];
        }

        return [
            Actions\EditAction::make()
                ->visible(fn($record) => ! \App\Models\Order::where('appointment_id', $record->id)->exists()),

            Actions\DeleteAction::make()
                ->visible(
                    fn($record) =>
                    $record->status === 'available'
                        && ! \App\Models\Order::where('appointment_id', $record->id)->exists()
                ),


        ];
    }
}
