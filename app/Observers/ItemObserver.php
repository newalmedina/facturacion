<?php

namespace App\Observers;

use App\Models\Item;
use Illuminate\Support\Facades\Storage;

class ItemObserver
{
    /**
     * Handle the Item "updated" event.
     *
     * @param  \App\Models\Item  $item
     * @return void
     */
    public function updated(Item $item)
    {
        // Verificar si la imagen ha cambiado y eliminar la anterior si es necesario
        if ($item->isDirty('image')) {
            $oldImage = $item->getOriginal('image');
            
            if ($oldImage && Storage::disk('public')->exists($oldImage)) {
                Storage::disk('public')->delete($oldImage);
            }
        }
    }

    /**
     * Handle the Item "deleted" event.
     *
     * @param  \App\Models\Item  $item
     * @return void
     */
    public function deleted(Item $item)
    {
        $this->deleteImage($item);
    }

    /**
     * Handle the Item "force deleted" event.
     *
     * @param  \App\Models\Item  $item
     * @return void
     */
    public function forceDeleted(Item $item)
    {
        $this->deleteImage($item);
    }

    /**
     * Elimina la imagen del almacenamiento si existe.
     *
     * @param  \App\Models\Item  $item
     * @return void
     */
    private function deleteImage(Item $item)
    {
        if ($item->image && Storage::disk('public')->exists($item->image)) {
            Storage::disk('public')->delete($item->image);
        }
    }
}
