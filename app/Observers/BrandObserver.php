<?php

namespace App\Observers;

use App\Models\Brand;
use Illuminate\Support\Facades\Storage;

class BrandObserver
{
    /**
     * Handle the Brand "created" event.
     *
     * @param  \App\Models\Brand  $brand
     * @return void
     */
    public function created(Brand $brand)
    {
        // No es necesario hacer nada aquí, ya que la imagen se sube cuando se crea.
    }

    /**
     * Handle the Brand "updated" event.
     *
     * @param  \App\Models\Brand  $brand
     * @return void
     */
    public function updated(Brand $brand)
    {
        // Verificar si la imagen ha cambiado y eliminar la anterior si es necesario
        if ($brand->isDirty('image')) {
            // Eliminar la imagen anterior si existe
            if ($brand->getOriginal('image') && Storage::exists($brand->getOriginal('image'))) {
                Storage::delete($brand->getOriginal('image'));
            }
        }
    }

    /**
     * Handle the Brand "deleted" event.
     *
     * @param  \App\Models\Brand  $brand
     * @return void
     */
    public function deleted(Brand $brand)
    {
        // Eliminar la imagen cuando el registro se elimina
        if ($brand->image && Storage::exists($brand->image)) {
            Storage::delete($brand->image);
        }
    }

    /**
     * Handle the Brand "restored" event.
     *
     * @param  \App\Models\Brand  $brand
     * @return void
     */
    public function restored(Brand $brand)
    {
        // No es necesario hacer nada aquí si es restaurado, pero puedes agregar lógica si lo deseas
    }

    /**
     * Handle the Brand "force deleted" event.
     *
     * @param  \App\Models\Brand  $brand
     * @return void
     */
    public function forceDeleted(Brand $brand)
    {
        // Eliminar la imagen al eliminarse de manera definitiva
        if ($brand->image && Storage::exists($brand->image)) {
            Storage::delete($brand->image);
        }
    }
}
