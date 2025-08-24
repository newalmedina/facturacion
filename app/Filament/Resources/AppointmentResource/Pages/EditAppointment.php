<?php

namespace App\Filament\Resources\AppointmentResource\Pages;

use App\Filament\Resources\AppointmentResource;
use App\Mail\AppointmentChangeStatusMail;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Mail;

class EditAppointment extends EditRecord
{
    protected static string $resource = AppointmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->visible(fn($record) => $record->status == 'available'),

            Actions\Action::make('sendEmailNotification')
                ->label('Enviar notificación por correo electrónico')
                ->icon('heroicon-o-envelope')
                ->color('success')
                ->visible(
                    fn($record) =>
                    in_array($record->status, ['cancelled', 'confirmed'])
                        && !empty($record->requester_name)
                )
                ->action(function ($record) {
                    Mail::to($record->requester_email)
                        ->send(new AppointmentChangeStatusMail($record));

                    $record->notification_sended = true;
                    $record->save();

                    Notification::make()
                        ->title('Notificación enviada por correo')
                        ->success()
                        ->send();
                }),
            // Botón para confirmar solicitud
            Action::make('confirmNotification')
                ->label('Confirmar solicitud') // solo icono
                ->icon('heroicon-m-check-circle')
                ->color('success')
                ->tooltip('Confirmar la solicitud y/o enviar notificación')
                ->visible(fn($record) => in_array($record->status, ['cancelled', 'pending_confirmation']))
                ->requiresConfirmation()
                ->modalHeading('Confirmar solicitud')
                ->modalSubmitActionLabel('Confirmar')
                ->modalCancelActionLabel('Regresar')
                ->form([
                    \Filament\Forms\Components\Checkbox::make('send_notification')
                        ->label('Enviar notificación por correo')
                        ->default(false),
                ])
                ->action(function ($record, array $data) {
                    $record->status = 'confirmed';
                    $record->save();

                    if (!empty($data['send_notification']) && $record->requester_email) {
                        Mail::to($record->requester_email)
                            ->send(new AppointmentChangeStatusMail($record));

                        $record->notification_sended = true;
                        $record->save();

                        Notification::make()
                            ->title('Solicitud confirmada y notificación enviada')
                            ->success()
                            ->send();
                    } else {
                        Notification::make()
                            ->title('Solicitud confirmada')
                            ->success()
                            ->send();
                    }
                }),

            // Botón para cancelar solicitud
            Action::make('cancelNotification')
                ->label('Cancelar solicitud ') // solo icono
                ->icon('heroicon-m-x-circle')
                ->color('danger')
                ->tooltip('Cancelar la solicitud y/o enviar notificación')
                ->visible(fn($record) => in_array($record->status, ['pending_confirmation', 'confirmed']))
                ->requiresConfirmation()
                ->modalHeading('Cancelar solicitud')
                ->modalSubmitActionLabel('Cancelar')
                ->modalCancelActionLabel('Regresar')
                ->form([
                    \Filament\Forms\Components\Checkbox::make('send_notification')
                        ->label('Enviar notificación por correo')
                        ->default(false),
                ])
                ->action(function ($record, array $data) {
                    $record->status = 'cancelled';
                    $record->save();

                    if (!empty($data['send_notification']) && $record->requester_email) {
                        Mail::to($record->requester_email)
                            ->send(new AppointmentChangeStatusMail($record));

                        $record->notification_sended = true;
                        $record->save();

                        Notification::make()
                            ->title('Solicitud cancelada y notificación enviada')
                            ->warning() // amarillo tipo warning
                            ->send();
                    } else {
                        Notification::make()
                            ->title('Solicitud cancelada')
                            ->warning()
                            ->send();
                    }
                }),
        ];
    }

    protected function getCancelFormAction(): Action
    {
        return Action::make('cancel')
            ->label(__('filament-panels::resources/pages/edit-record.form.actions.cancel.label'))
            ->url($this->getResource()::getUrl('index'))
            ->color('gray');
    }
}
