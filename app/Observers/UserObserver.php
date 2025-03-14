<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Facades\Storage;

class UserObserver
{
    /**
     * Handle the User "created" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function created(User $user)
    {
        // No es necesario hacer nada aquí, ya que la imagen se sube cuando se crea.
    }

    /**
     * Handle the User "updated" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function updated(User $user)
    {
        // Verificar si la imagen ha cambiado y eliminar la anterior si es necesario
        if ($user->isDirty('image')) {
            // Eliminar la imagen anterior si existe
            if ($user->getOriginal('image') && Storage::exists($user->getOriginal('image'))) {
                Storage::delete($user->getOriginal('image'));
            }
        }
    }

    /**
     * Handle the User "deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function deleted(User $user)
    {
        // Eliminar la imagen cuando el registro se elimina
        if ($user->image && Storage::exists($user->image)) {
            Storage::delete($user->image);
        }
    }

    /**
     * Handle the User "restored" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function restored(User $user)
    {
        // No es necesario hacer nada aquí si es restaurado, pero puedes agregar lógica si lo deseas
    }

    /**
     * Handle the User "force deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        // Eliminar la imagen al eliminarse de manera definitiva
        if ($user->image && Storage::exists($user->image)) {
            Storage::delete($user->image);
        }
    }
}
