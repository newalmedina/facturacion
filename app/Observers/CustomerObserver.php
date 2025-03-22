<?php

namespace App\Observers;

use App\Models\Customer;
use Illuminate\Support\Facades\Storage;

class CustomerObserver
{
    /**
     * Handle the Customer "updated" event.
     *
     * @param  \App\Models\Customer  $customer
     * @return void
     */
    public function updated(Customer $customer)
    {
        // Verificar si la imagen ha cambiado y eliminar la anterior si es necesario
        if ($customer->isDirty('image')) {
            $oldImage = $customer->getOriginal('image');
            
            if ($oldImage && Storage::disk('public')->exists($oldImage)) {
                Storage::disk('public')->delete($oldImage);
            }
        }
    }

    /**
     * Handle the Customer "deleted" event.
     *
     * @param  \App\Models\Customer  $customer
     * @return void
     */
    public function deleted(Customer $customer)
    {
        $this->deleteImage($customer);
    }

    /**
     * Handle the Customer "force deleted" event.
     *
     * @param  \App\Models\Customer  $customer
     * @return void
     */
    public function forceDeleted(Customer $customer)
    {
        $this->deleteImage($customer);
    }

    /**
     * Elimina la imagen del almacenamiento si existe.
     *
     * @param  \App\Models\Customer  $customer
     * @return void
     */
    private function deleteImage(Customer $customer)
    {
        if ($customer->image && Storage::disk('public')->exists($customer->image)) {
            Storage::disk('public')->delete($customer->image);
        }
    }
}