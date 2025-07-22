<?php

namespace App\Filament\Pages;

use App\Jobs\RunBackupJob;
use ShuvroRoy\FilamentSpatieLaravelBackup\Pages\Backups as BaseBackups;

class Backups extends BaseBackups
{
    protected static ?string $navigationIcon = 'heroicon-o-cpu-chip';
    protected static ?string $navigationGroup = 'Configuraciones';
    protected static ?int $navigationSort = 81;


    public static function getNavigationGroup(): ?string
    {
        return 'Configuraciones';
    }
    public function runBackup()
    {
        RunBackupJob::dispatch();

        $this->notify('success', 'El backup se está ejecutando en segundo plano.');
    }
}
