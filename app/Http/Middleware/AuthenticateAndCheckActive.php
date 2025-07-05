<?php

namespace App\Http\Middleware;

use Filament\Facades\Filament;
use Filament\Models\Contracts\FilamentUser;
use Filament\Http\Middleware\Authenticate as FilamentAuthenticate;
use Illuminate\Database\Eloquent\Model;

class AuthenticateAndCheckActive extends FilamentAuthenticate
{
    /**
     * @param  array<string>  $guards
     */
    protected function authenticate($request, array $guards): void
    {
        $guard = Filament::auth();

        if (! $guard->check()) {
            $this->unauthenticated($request, $guards);
            return;
        }

        $this->auth->shouldUse(Filament::getAuthGuard());

        /** @var Model $user */
        $user = $guard->user();

        $panel = Filament::getCurrentPanel();

        abort_if(
            $user instanceof FilamentUser ?
                (! $user->canAccessPanel($panel)) : (config('app.env') !== 'local'),
            403,
        );

        if (!$user->active) {
            $guard->logout();

            abort(403, 'Tu cuenta está desactivada.');
        }
    }
}
