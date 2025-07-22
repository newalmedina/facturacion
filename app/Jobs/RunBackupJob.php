<?php

namespace App\Jobs;

use App\Notifications\BackupStatusNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Notification;

class RunBackupJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        try {
            // Ejecutar el comando de backup
            $exitCode = Artisan::call('backup:run');

            if ($exitCode === 0) {
                $this->sendNotification('Éxito', 'El backup se completó correctamente.');
            } else {
                $this->sendNotification('Error', 'El backup finalizó con código de error: ' . $exitCode);
            }
        } catch (\Exception $e) {
            $this->sendNotification('Error', 'Excepción al ejecutar el backup: ' . $e->getMessage());
        }
    }

    protected function sendNotification(string $status, string $message): void
    {
        Notification::route('mail', 'ing.newal.medina@gmail.com')
            ->notify(new BackupStatusNotification($status, $message));
    }
}
