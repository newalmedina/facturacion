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
            \Log::info('🟡 Iniciando ejecución del comando backup:run');

            // Ejecutar el comando de backup
            $exitCode = Artisan::call('backup:run');

            \Log::info("🟢 Comando backup:run ejecutado con código de salida: {$exitCode}");

            if ($exitCode === 0) {
                \Log::info('✅ Backup finalizado correctamente, se enviará notificación de éxito.');
                $this->sendNotification('Éxito', 'El backup se completó correctamente.');
            } else {
                \Log::warning("⚠️ Backup con errores, código: {$exitCode}. Se enviará notificación de error.");
                $this->sendNotification('Error', 'El backup finalizó con código de error: ' . $exitCode);
            }
        } catch (\Exception $e) {
            \Log::error("❌ Excepción al ejecutar backup: " . $e->getMessage());
            $this->sendNotification('Error', 'Excepción al ejecutar el backup: ' . $e->getMessage());
        }
    }

    protected function sendNotification(string $status, string $message): void
    {
        Notification::route('mail', 'el.solitions@gmail.com')
            ->notify(new BackupStatusNotification($status, $message));
    }
}
