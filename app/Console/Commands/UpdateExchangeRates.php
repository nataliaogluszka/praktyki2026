<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Setting;

class UpdateExchangeRates extends Command
{
    protected $signature = 'currency:update';
    protected $description = 'Pobiera aktualne kursy walut z API i zapisuje w ustawieniach';

    public function handle()
    {
        $this->info('Pobieranie kursów walut...');

        $response = Http::get("https://open.er-api.com/v6/latest/PLN");

        if ($response->successful()) {
            $rates = $response->json()['rates'];
            
            $dataToSave = [
                'EUR' => $rates['EUR'],
                'USD' => $rates['USD'],
                'GBP' => $rates['GBP'],
                'updated_at' => now()->toDateTimeString()
            ];

            Setting::updateOrCreate(
                ['key' => 'exchange_rates'],
                ['value' => json_encode($dataToSave)]
            );

            $this->info('Kursy zostały zaktualizowane pomyślnie.');
        } else {
            $this->error('Błąd podczas pobierania kursów.');
        }
    }
}