<?php
 
namespace App\Filament\AvatarProviders;
 
use Filament\AvatarProviders\Contracts;
use Filament\Facades\Filament;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
 
class AutenticatedUserAvatar implements Contracts\AvatarProvider
{
    public function get(Model | Authenticatable $record): string
    {
        return $record->getFilamentAvatarUrl();
       
 
        return 'https://source.boringavatars.com/beam/120/' . urlencode($name);
    }
}