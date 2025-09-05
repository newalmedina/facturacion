<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Appointment;
use App\Models\User;

class CalendarPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationGroup = 'Gestión de citas';
    protected static ?int $navigationSort = 47;
    protected static string $view = 'filament.pages.calendar-appointment';
    public array $statusList = [];
    public ?string $selectedStatus = null;
    protected static ?string $title = 'Calendario de citas';
    public array $workerList = [];
    public ?int $selectedWorker = null;

    public function mount()
    {
        // Estados
        $statuses = ['available', 'confirmed', 'pending_confirmation', 'cancelled'];
        $this->statusList = collect($statuses)->mapWithKeys(fn($status) => [
            $status => (new Appointment(['status' => $status]))->status_name_formatted
        ])->toArray();

        // Trabajadores
        $this->workerList = User::canAppointment()->pluck('name', 'id')->toArray();

        $this->selectedStatus = '';
        $this->selectedWorker = null;
    }
}
