<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class EraseBackupOld extends Command
{
    protected $signature = 'erase:backup_old';
    protected $description = 'Elimina los backups antiguos de más de 20 días';

    public $totalDias = 10;
    public function handle()
    {
        $this->info('Buscando backups antiguos para eliminar...');

        $backupPath = 'laravel-backup/laravel-backup'; // dentro de storage/app

        // Obtener todos los archivos en la carpeta
        $files = Storage::files($backupPath);

        $deletedCount = 0;
        $now = Carbon::now();

        foreach ($files as $file) {
            $fullPath = storage_path('app/' . $file);

            if (!file_exists($fullPath)) {
                continue;
            }

            $lastModified = Carbon::createFromTimestamp(filemtime($fullPath));

            // Si el archivo tiene más de 20 días
            if ($lastModified->diffInDays($now) >$this->totalDias) {
                unlink($fullPath);
                $this->info("Eliminado: {$file}");
                $deletedCount++;
            }
        }

        $this->info("Proceso completado. Total archivos eliminados: {$deletedCount}");
    }
}
