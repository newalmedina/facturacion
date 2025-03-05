<?php

namespace App\Observers;

use App\Models\Supplier;
use Illuminate\Support\Facades\Storage;

class SupplierObserver
{
    /**
     * Handle the Supplier "created" event.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return void
     */
    public function created(Supplier $supplier)
    {
        // No es necesario hacer nada aquí, ya que la imagen se sube cuando se crea.
    }

    /**
     * Handle the Supplier "updated" event.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return void
     */
    public function updated(Supplier $supplier)
    {
        // Verificar si la imagen ha cambiado y eliminar la anterior si es necesario
        if ($supplier->isDirty('image')) {
            // Eliminar la imagen anterior si existe
            if ($supplier->getOriginal('image') && Storage::exists($supplier->getOriginal('image'))) {
                Storage::delete($supplier->getOriginal('image'));
            }
        }
    }

    /**
     * Handle the Supplier "deleted" event.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return void
     */
    public function deleted(Supplier $supplier)
    {
        // Eliminar la imagen cuando el registro se elimina
        if ($supplier->image && Storage::exists($supplier->image)) {
            Storage::delete($supplier->image);
        }
    }

    /**
     * Handle the Supplier "restored" event.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return void
     */
    public function restored(Supplier $supplier)
    {
        // No es necesario hacer nada aquí si es restaurado, pero puedes agregar lógica si lo deseas
    }

    /**
     * Handle the Supplier "force deleted" event.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return void
     */
    public function forceDeleted(Supplier $supplier)
    {
        // Eliminar la imagen al eliminarse de manera definitiva
        if ($supplier->image && Storage::exists($supplier->image)) {
            Storage::delete($supplier->image);
        }
    }
}
