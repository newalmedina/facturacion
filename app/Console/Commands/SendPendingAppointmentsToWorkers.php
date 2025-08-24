<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Appointment;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\PendingAppointmentsToWorkerMail;

class SendPendingAppointmentsToWorkers extends Command
{
    protected $signature = 'appointments:send-pending-to-workers';
    protected $description = 'Send email notifications to workers with pending appointments to confirm';

    public function handle(): int
    {
        $now = Carbon::now();

        // citas desde hoy en adelante con estado "pending"
        $appointments = Appointment::active()
            ->where('status', 'pending_confirmation')
            ->whereDate('date', '>=', $now->toDateString())
            ->with('worker')
            ->get();

        // agrupar por trabajador
        $appointmentsByWorker = $appointments->groupBy('worker_id');

        foreach ($appointmentsByWorker as $workerId => $workerAppointments) {
            $worker = $workerAppointments->first()->worker ?? null;

            if ($worker && $worker->email) {
                Mail::to($worker->email)
                    ->send(new PendingAppointmentsToWorkerMail($worker, $workerAppointments));

                $this->info("Pending appointments email sent to worker {$worker->name}");
            }
        }

        return Command::SUCCESS;
    }
}
