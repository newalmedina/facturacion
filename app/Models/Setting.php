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
    /*public function getGeneralAttribute()
    {
        $settings = $this->where('key', 'like', 'general.%')
            ->get()
            ->pluck('value', 'key')
            ->mapWithKeys(function ($value, $key) {
                return [str_replace('general.', '', $key) => $value];
            });
        
        $data = $settings->toArray();
        
        return empty($data) ? null : (object) array_merge([], $data);
    }*/
    // app/Models/Setting.php

    public function getGeneralAttribute()
    {
        $settings = $this->where('key', 'like', 'general.%')
            ->get()
            ->pluck('value', 'key')
            ->mapWithKeys(function ($value, $key) {
                return [str_replace('general.', '', $key) => $value];
            });

        $data = $settings->toArray();

        // Convertir a objeto y añadir el atributo 'image_base64'
        if (!empty($data)) {
            $object = (object) array_merge([], $data);

            // Convertir imagen a base64 si existe
            if (!empty($object->image)) {
                $imagePath = storage_path('app/public/' . str_replace('"', '', $object->image));
                if (file_exists($imagePath)) {
                    $imageData = base64_encode(file_get_contents($imagePath));
                    $extension = pathinfo($imagePath, PATHINFO_EXTENSION);
                    $object->image_base64 = 'data:image/' . $extension . ';base64,' . $imageData;
                } else {
                    $object->image_base64 = null;
                }
            } else {
                $object->image_base64 = null;
            }

            return $object;
        }

        return null;
    }


    /*$settings = Setting::first();
    $generalSettings = $settings->general;
    
    dd($generalSettings->imagef); // Accede como propiedad de objeto*/
}
