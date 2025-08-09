<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class BackupDownloadController extends Controller
{
    public function download(string $filepath)
    {
        // Control básico de permisos por email (ajusta o cambia según tu lógica)
        if (auth()->user()->email !== 'el.solitions@gmail.com') {
            abort(403, 'No tienes permiso para descargar este archivo.');
        }

        $disk = Storage::disk(config('backup.backup.destination.disks')[0]);

        if (! $disk->exists($filepath)) {
            abort(404);
        }

        return response()->download($disk->path($filepath));
    }
}
