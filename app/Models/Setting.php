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

        if (!empty($data)) {
            // Limpiar valores y convertir tipos
            $cleanData = [];
            foreach ($data as $key => $value) {
                // Eliminar comillas dobles al inicio y al final
                $value = trim($value, '"');

                // Convertir "true"/"false" a booleanos
                if ($value === 'true') {
                    $value = true;
                } elseif ($value === 'false') {
                    $value = false;
                }
                // Convertir números enteros si es posible
                elseif (ctype_digit($value)) {
                    $value = (int) $value;
                }

                $cleanData[$key] = $value;
            }

            $object = (object) $cleanData;

            // Convertir imagen a base64 si existe
            if (!empty($object->image)) {
                $imagePath = storage_path('app/public/' . $object->image);

                if (is_file($imagePath)) {
                    $imageData = base64_encode(file_get_contents($imagePath));
                    $extension = pathinfo($imagePath, PATHINFO_EXTENSION);
                    $object->image_base64 = 'data:image/' . $extension . ';base64,' . $imageData;
                } else {
                    $object->image_base64 = null;
                }
            } else {
                $object->image_base64 = null;
            }
            // dd($object);
            return $object;
        }

        return null;
    }



    /*$settings = Setting::first();
    $generalSettings = $settings->general;
    
    dd($generalSettings->imagef); // Accede como propiedad de objeto*/
}
