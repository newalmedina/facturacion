<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Appointment;
use Illuminate\Support\Carbon;

class SendAppointmentReminders extends Command
{
    protected $signature = 'appointments:send-reminders';
    protected $description = 'Send reminder emails for upcoming confirmed appointments';

    // Variables configurables
    public $firstReminderHours = 24;     // primer aviso (24 horas antes)
    public $secondReminderHours = 3;     // segundo aviso (3 horas antes)
    public $firstReminderWindowMinutes = 30; // ventana de ejecución del primer aviso
    public $secondReminderWindowMinutes = 15; // ventana de ejecución del segundo aviso
    public $reminderCooldownHours = 1;   // tiempo mínimo entre recordatorios para no duplicar

    public function handle(): int
    {
        $now = Carbon::now();

        // Buscar citas confirmed desde hoy en adelante, activas y con email válido
        $appointments = Appointment::active()
            ->where('status', 'confirmed')
            ->whereDate('date', '>=', $now->toDateString())
            ->whereNotNull('requester_email')
            ->get();

        foreach ($appointments as $appointment) {
            // Construir fecha completa de la cita (date + start_time)
            $appointmentDateTime = $appointment->date->copy()
                ->setTimeFrom($appointment->start_time);

            // Primer aviso
            if ($now->between(
                $appointmentDateTime->copy()->subHours($this->firstReminderHours)->subMinutes($this->firstReminderWindowMinutes),
                $appointmentDateTime->copy()->subHours($this->firstReminderHours)->addMinutes($this->firstReminderWindowMinutes)
            )) {
                $this->sendReminder($appointment, $this->firstReminderHours);
            }
            //$this->sendReminder($appointment, $this->firstReminderHours);

            // Segundo aviso
            if ($now->between(
                $appointmentDateTime->copy()->subHours($this->secondReminderHours)->subMinutes($this->secondReminderWindowMinutes),
                $appointmentDateTime->copy()->subHours($this->secondReminderHours)->addMinutes($this->secondReminderWindowMinutes)
            )) {
                $this->sendReminder($appointment, $this->secondReminderHours);
            }
        }

        return Command::SUCCESS;
    }

    protected function sendReminder(Appointment $appointment, int $hoursBefore)
    {
        // Evitar duplicados
        $lastReminder = $appointment->last_reminder_sent ?? null;
        if ($lastReminder && Carbon::parse($lastReminder)->diffInHours(now()) < $this->reminderCooldownHours) {
            return;
        }

        // Enviar mail usando tu Mailable
        \Mail::to($appointment->requester_email)
            ->send(new \App\Mail\AppointmentReminderMail($appointment, $hoursBefore));

        // Actualizar último recordatorio
        $appointment->last_reminder_sent = now();
        $appointment->save();

        $this->info("Reminder sent for appointment {$appointment->id} ($hoursBefore hours before).");
    }
}
