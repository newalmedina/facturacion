<?php

namespace App\Filament\Resources\AppointmentTemplateResource\Pages;

use App\Filament\Resources\AppointmentTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Str;
use Filament\Actions\Action;

class EditAppointmentTemplate extends EditRecord
{
    protected static string $resource = AppointmentTemplateResource::class;
    protected array $slotsData = [];

    protected function getHeaderActions(): array
    {
        return [
            //Actions\DeleteAction::make(),
        ];
    }
    protected function getCancelFormAction(): Action
    {
        return Action::make('cancel')
            ->label(__('filament-panels::resources/pages/edit-record.form.actions.cancel.label'))
            ->url($this->getResource()::getUrl('index'))
            ->color('gray');
    }
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $grouped = [];

        foreach ($this->record->slots->groupBy('group') as $groupSlots) {
            $days = $groupSlots->pluck('day_of_week')->unique()->values()->toArray();
            $timeRanges = $groupSlots->map(fn($slot) => [
                'start_time' => $slot->start_time,
                'end_time' => $slot->end_time,
            ])->unique(function ($range) {
                return $range['start_time'] . '|' . $range['end_time'];
            })->values()->toArray();

            $grouped[] = [
                'days_of_week' => $days,
                'time_ranges' => $timeRanges,
            ];
        }

        $data['slots'] = $grouped;

        return $data;
    }


    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Guardar en una propiedad temporal para usar en afterSave
        $this->slotsData = $data['slots'] ?? [];

        unset($data['slots']);

        return $data;
    }
    protected function afterSave(): void
    {
        $record = $this->record;

        // Eliminar los anteriores (opcional, dependiendo si estás editando o creando)
        $record->slots()->delete();
        foreach ($this->slotsData as $slot) {
            $days = $slot['days_of_week'] ?? [];
            $ranges = $slot['time_ranges'] ?? [];
            $group = Str::uuid(); // 🔑 ID de grupo para este bloque
            foreach ($days as $day) {
                foreach ($ranges as $range) {

                    $record->slots()->create([
                        'day_of_week' => $day,
                        'start_time' => $range['start_time'],
                        'end_time' => $range['end_time'],
                        'group' => $group
                    ]);
                }
            }
        }
    }
}
