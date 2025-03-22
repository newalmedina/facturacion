<?php

namespace App\Observers;

use App\Models\Supplier;
use Illuminate\Support\Facades\Storage;

class SupplierObserver
{
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
            $oldImage = $supplier->getOriginal('image');
            
            if ($oldImage && Storage::disk('public')->exists($oldImage)) {
                Storage::disk('public')->delete($oldImage);
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
        $this->deleteImage($supplier);
    }

    /**
     * Handle the Supplier "force deleted" event.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return void
     */
    public function forceDeleted(Supplier $supplier)
    {
        $this->deleteImage($supplier);
    }

    /**
     * Elimina la imagen del almacenamiento si existe.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return void
     */
    private function deleteImage(Supplier $supplier)
    {
        if ($supplier->image && Storage::disk('public')->exists($supplier->image)) {
            Storage::disk('public')->delete($supplier->image);
        }
    }
}
