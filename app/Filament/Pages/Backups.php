<?php

namespace App\Filament\Pages;

use App\Jobs\RunBackupJob;
use ShuvroRoy\FilamentSpatieLaravelBackup\Pages\Backups as BaseBackups;

use ShuvroRoy\FilamentSpatieLaravelBackup\Enums\Option;
use ShuvroRoy\FilamentSpatieLaravelBackup\Jobs\CreateBackupJob;
use Filament\Notifications\Notification;

class Backups extends BaseBackups
{
    protected static ?string $navigationIcon = 'heroicon-o-cpu-chip';
    protected static ?string $navigationGroup = 'Configuraciones';
    protected static ?int $navigationSort = 81;

    protected function monitoredBackupName(): string
    {
        return 'databasebackup';
    }
    public static function getNavigationGroup(): ?string
    {
        return 'Configuraciones';
    }
    // Sobrescribes para que el botón lance directo el backup de base de datos
    public function openOptionModal(): void
    {
        $this->create('database');
    }

    // Opcionalmente, puedes sobrescribir create para personalizar notificación o job
    public function create(string $option = ''): void
    {
        CreateBackupJob::dispatch(Option::ONLY_DB, 300)
            ->onQueue('default')
            ->afterResponse();
        Notification::make()
            ->title('El backup solo de base de datos se está ejecutando.')
            ->success()
            ->send();
    }
}
