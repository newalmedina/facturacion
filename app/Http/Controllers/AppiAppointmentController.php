<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;

class AppiAppointmentController extends Controller
{
    public function getAppointmentByRangeOfDate(Request $request)
    {
        $user = $request->user();

        // 🔎 Query base
        $query = Appointment::query()
            ->whereBetween('date', [
                $request->start_date,
                $request->end_date
            ])
            ->where('active', 1);

        // ⚠️ Filtro opcional por worker_id
        if ($request->filled('worker_id')) {
            $query->where('worker_id', $request->worker_id);
        } else  if (!$user->can_admin_panel) {
            $query->where('worker_id', $user->worker_id);
        }

        // 📦 Resultado
        $appointments = $query
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        return response()->json([
            'success' => true,
            'count' => $appointments->count(),
            'data' => $appointments
        ]);
    }
}
