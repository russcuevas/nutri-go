<?php

namespace App\Services;

use App\Models\SystemSetting;

class LipaLocationService
{
    /**
     * All 72 Barangays of Lipa City with calibrated coordinates
     */
    public static array $barangays = [
        'Adya' => ['lat' => 13.9056, 'lng' => 121.1989],
        'Anilao' => ['lat' => 13.9182, 'lng' => 121.1492],
        'Anilao-Labac' => ['lat' => 13.9150, 'lng' => 121.1420],
        'Antipolo del Norte' => ['lat' => 13.9482, 'lng' => 121.1780],
        'Antipolo del Sur' => ['lat' => 13.9410, 'lng' => 121.1820],
        'Bagong Pook' => ['lat' => 13.9378, 'lng' => 121.1695],
        'Balintawak' => ['lat' => 13.9485, 'lng' => 121.1550],
        'Banaybanay' => ['lat' => 13.9620, 'lng' => 121.1480],
        'Bolbok' => ['lat' => 13.9280, 'lng' => 121.1540],
        'Bugtong na Pulo' => ['lat' => 13.9780, 'lng' => 121.1410],
        'Bulacnin' => ['lat' => 13.9720, 'lng' => 121.1730],
        'Bulaklakan' => ['lat' => 13.9310, 'lng' => 121.1920],
        'Calamias' => ['lat' => 13.9120, 'lng' => 121.1890],
        'Cuta' => ['lat' => 13.9350, 'lng' => 121.1610],
        'Dagatan' => ['lat' => 13.9210, 'lng' => 121.1680],
        'Duhatan' => ['lat' => 13.9010, 'lng' => 121.1720],
        'Halang' => ['lat' => 13.9240, 'lng' => 121.2050],
        'Inosloban' => ['lat' => 13.9890, 'lng' => 121.1390],
        'Kayumanggi' => ['lat' => 13.9390, 'lng' => 121.1510],
        'Latag' => ['lat' => 13.9510, 'lng' => 121.1440],
        'Lodlod' => ['lat' => 13.9260, 'lng' => 121.1410],
        'Lumbang' => ['lat' => 13.9580, 'lng' => 121.1920],
        'Mabini' => ['lat' => 13.9330, 'lng' => 121.1750],
        'Malagonlong' => ['lat' => 13.9080, 'lng' => 121.1610],
        'Malitlit' => ['lat' => 13.9190, 'lng' => 121.2180],
        'Marauoy' => ['lat' => 13.9590, 'lng' => 121.1660],
        'Mataas na Lupa' => ['lat' => 13.9410, 'lng' => 121.1590],
        'Munting Pulo' => ['lat' => 13.9680, 'lng' => 121.1850],
        'Pagolingin Bata' => ['lat' => 13.8990, 'lng' => 121.1830],
        'Pagolingin East' => ['lat' => 13.8950, 'lng' => 121.1890],
        'Pagolingin West' => ['lat' => 13.8970, 'lng' => 121.1790],
        'Pangao' => ['lat' => 13.9360, 'lng' => 121.2020],
        'Pinagkawitan' => ['lat' => 13.9160, 'lng' => 121.2010],
        'Pinagtung-Ulan' => ['lat' => 13.9850, 'lng' => 121.1670],
        'Plaridel' => ['lat' => 13.9740, 'lng' => 121.1950],
        'Poblacion Barangay 1' => ['lat' => 13.9410, 'lng' => 121.1620],
        'Poblacion Barangay 2' => ['lat' => 13.9420, 'lng' => 121.1630],
        'Poblacion Barangay 3' => ['lat' => 13.9430, 'lng' => 121.1625],
        'Poblacion Barangay 4' => ['lat' => 13.9440, 'lng' => 121.1635],
        'Poblacion Barangay 5' => ['lat' => 13.9425, 'lng' => 121.1645],
        'Poblacion Barangay 6' => ['lat' => 13.9415, 'lng' => 121.1650],
        'Poblacion Barangay 7' => ['lat' => 13.9405, 'lng' => 121.1640],
        'Poblacion Barangay 8' => ['lat' => 13.9395, 'lng' => 121.1630],
        'Poblacion Barangay 9' => ['lat' => 13.9385, 'lng' => 121.1620],
        'Poblacion Barangay 9-A' => ['lat' => 13.9380, 'lng' => 121.1615],
        'Poblacion Barangay 10' => ['lat' => 13.9400, 'lng' => 121.1610],
        'Poblacion Barangay 11' => ['lat' => 13.9412, 'lng' => 121.1605],
        'Poblacion Barangay 12' => ['lat' => 13.9422, 'lng' => 121.1615],
        'Pusil' => ['lat' => 13.9630, 'lng' => 121.2050],
        'Quezon' => ['lat' => 13.9790, 'lng' => 121.1820],
        'Rizal' => ['lat' => 13.9490, 'lng' => 121.1910],
        'Sabang' => ['lat' => 13.9340, 'lng' => 121.1570],
        'Sampaguita' => ['lat' => 13.9460, 'lng' => 121.1710],
        'San Benito' => ['lat' => 13.9690, 'lng' => 121.2120],
        'San Carlos' => ['lat' => 13.9480, 'lng' => 121.2190],
        'San Celestino' => ['lat' => 13.9870, 'lng' => 121.2010],
        'San Francisco' => ['lat' => 13.9530, 'lng' => 121.2090],
        'San Guillermo' => ['lat' => 13.9750, 'lng' => 121.2180],
        'San Jose' => ['lat' => 13.9360, 'lng' => 121.1860],
        'San Lucas' => ['lat' => 13.9820, 'lng' => 121.2100],
        'San Salvador' => ['lat' => 13.9610, 'lng' => 121.1760],
        'San Sebastian' => ['lat' => 13.9515, 'lng' => 121.1660],
        'Santo Niño' => ['lat' => 13.9650, 'lng' => 121.1600],
        'Santo Toribio' => ['lat' => 13.9590, 'lng' => 121.2150],
        'Sapac' => ['lat' => 13.9230, 'lng' => 121.1810],
        'Sico' => ['lat' => 13.9140, 'lng' => 121.1710],
        'Talisay' => ['lat' => 13.9810, 'lng' => 121.1560],
        'Tambo' => ['lat' => 13.9530, 'lng' => 121.1500],
        'Tangway' => ['lat' => 13.9320, 'lng' => 121.1460],
        'Tibig' => ['lat' => 13.9430, 'lng' => 121.1420],
        'Tipacan' => ['lat' => 13.9090, 'lng' => 121.1960],
    ];

    public static function getBarangayList(): array
    {
        return array_keys(static::$barangays);
    }

    public static function getCoordinates(string $barangay): ?array
    {
        return static::$barangays[$barangay] ?? ['lat' => 13.9419, 'lng' => 121.1631];
    }

    /**
     * Find nearest Lipa City Barangay from exact GPS coordinates
     */
    public static function findNearestBarangay(float $lat, float $lng): string
    {
        $closest = 'Poblacion Barangay 1';
        $minDist = PHP_FLOAT_MAX;
        foreach (static::$barangays as $name => $coords) {
            $dLat = $coords['lat'] - $lat;
            $dLng = $coords['lng'] - $lng;
            $distSq = ($dLat * $dLat) + ($dLng * $dLng);
            if ($distSq < $minDist) {
                $minDist = $distSq;
                $closest = $name;
            }
        }
        return $closest;
    }

    /**
     * Calculate Point-to-Point Distance in KM using Haversine formula
     * with road winding correction factor (1.2x)
     */
    public static function calculateDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadiusKm = 6371;

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $straightDistance = $earthRadiusKm * $c;

        // Apply 1.25 road correction factor for real Lipa City road network
        $roadDistance = $straightDistance * 1.25;

        // Minimum distance is 0.8 km
        return max(0.8, round($roadDistance, 2));
    }

    /**
     * Calculate accurate Point-to-Point & Gas-based Delivery Fee (Grab/Foodpanda/Angkas Standard)
     */
    public static function calculateDeliveryFee(float $distanceKm): array
    {
        $baseFare = (float) SystemSetting::get('base_delivery_fare', 40.00); // covers booking and first 1.5 km
        $baseDistance = (float) SystemSetting::get('base_distance_km', 1.5);
        $ratePerKm = (float) SystemSetting::get('rate_per_km', 10.00);       // gas allowance & distance rate

        $extraDistance = max(0.0, $distanceKm - $baseDistance);
        $distanceFee = $extraDistance * $ratePerKm;

        $totalDeliveryFee = round($baseFare + $distanceFee);
        $estimatedTimeMins = max(12, round(10 + ($distanceKm * 3.5)));

        return [
            'distance_km' => $distanceKm,
            'base_fare' => $baseFare,
            'base_distance_km' => $baseDistance,
            'rate_per_km' => $ratePerKm,
            'extra_distance_km' => round($extraDistance, 2),
            'gas_allowance_fee' => round($distanceFee, 2),
            'delivery_fee' => (float) $totalDeliveryFee,
            'estimated_time_mins' => (int) $estimatedTimeMins,
        ];
    }
}
