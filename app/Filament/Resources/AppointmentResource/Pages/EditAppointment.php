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
