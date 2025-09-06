<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Country;
use App\Models\State;
use GuzzleHttp\Client;

class TranslateLocations extends Command
{
    protected $signature = 'locations:translate';
    protected $description = 'Traduce los nombres de los países a español usando MyMemory API';

    public function handle()
    {
        $this->info('Iniciando traducción de países a español...');

        $countries = Country::where('translated', 0)->get();

        foreach ($countries as $country) {
            $spanishName = $country->translations['es'] ?? null;
            if ($spanishName) {
                $country->name = $spanishName;
                $country->translated = true;

                $this->info("{$country->id} - {$country->name} actualizado.");
            } else {
                $country->translated = true;
                $this->info("{$country->id} - {$country->name} no tiene traducción.");
            }
            $country->save();
        }
        $client = new Client();
        $states = State::where("translated", 0)->get();

        foreach ($states as $state) {
            $oldName = $state->name;

            try {
                $response = $client->get('https://api.mymemory.translated.net/get', [
                    'query' => [
                        'q' => $oldName,
                        'langpair' => 'en|es', // traducir de inglés a español
                    ],
                    'timeout' => 10,
                ]);

                $result = json_decode($response->getBody(), true);

                // Obtener traducción
                $newName = $result['responseData']['translatedText'] ?? $oldName;

                // Guardar en BD
                $state->name = $newName;
                $state->translated = true;
                $state->save();
                $this->info("{$oldName} → {$newName}");
            } catch (\Exception $e) {
                $this->error("Error traduciendo {$oldName}: " . $e->getMessage());
            }
        }

        $this->info('Traducción de países completada.');
    }
}
