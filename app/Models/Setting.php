<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $table = 'settings';
    protected $guarded = [];
      /**
     * Accesor para obtener los valores como un objeto o null si no existen.
     */
    public function getGeneralAttribute()
    {
        $settings = $this->where('key', 'like', 'general.%')
            ->get()
            ->pluck('value', 'key')
            ->mapWithKeys(function ($value, $key) {
                return [str_replace('general.', '', $key) => $value];
            });
        
        $data = $settings->toArray();
        
        return empty($data) ? null : (object) array_merge([], $data);
    }

    /*$settings = Setting::first();
    $generalSettings = $settings->general;
    
    dd($generalSettings->imagef); // Accede como propiedad de objeto*/
}
