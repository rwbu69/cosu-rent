<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ShippingService
{
    /**
     * Get shipping costs from the API.
     *
     * @param string $destinationVillageCode
     * @param int|float $weightInKg
     * @return array|null
     */
    public function getShippingCosts(string $destinationVillageCode, $weightInKg)
    {
        $originCode = env('RAJAONGKIR_ORIGIN_ID') ?? env('ORIGIN_VILLAGE_CODE');
        $apiKey = env('RAJAONGKIR_API_KEY');

        if (!$originCode || !$apiKey) {
            Log::error('ShippingService: Missing RAJAONGKIR_ORIGIN_ID or RAJAONGKIR_API_KEY in .env');
            return null;
        }

        $couriers = ['jne', 'sicepat', 'jnt'];
        $allOptions = [];
        $weightInGrams = max(100, $weightInKg * 1000); // minimal 100 gram

        foreach ($couriers as $courier) {
            try {
                $response = Http::withHeaders([
                    'key' => $apiKey,
                ])->asForm()->post('https://rajaongkir.komerce.id/api/v1/calculate/domestic-cost', [
                    'origin' => $originCode,
                    'destination' => $destinationVillageCode,
                    'weight' => $weightInGrams,
                    'courier' => $courier,
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    if (isset($data['meta']['status']) && $data['meta']['status'] === 'success' && !empty($data['data'])) {
                        // data contains array of services directly based on RajaOngkir Komerce spec
                        $services = $data['data'] ?? [];
                        foreach ($services as $service) {
                            $serviceName = strtoupper($service['service'] ?? '');
                            
                            // Kecualikan kargo dan layanan kendaraan
                            $excludedServices = ['JTR', 'JTR<130', 'JTR>130', 'JTR>200', 'GOKIL'];
                            if (in_array($serviceName, $excludedServices)) {
                                continue;
                            }

                            $allOptions[] = [
                                'courier_code' => $courier . '_' . strtolower($service['service'] ?? ''),
                                'courier_name' => strtoupper($courier) . ' - ' . ($service['service'] ?? 'Regular'),
                                'price' => $service['cost'] ?? 0,
                                'estimation' => $service['etd'] ?? '-'
                            ];
                        }
                    }
                } else {
                    Log::error("ShippingService API Error for $courier", [
                        'status' => $response->status(),
                        'body' => $response->body()
                    ]);
                }
            } catch (\Exception $e) {
                Log::error("ShippingService Exception for $courier: " . $e->getMessage());
            }
        }

        return empty($allOptions) ? null : $allOptions;
    }
}
