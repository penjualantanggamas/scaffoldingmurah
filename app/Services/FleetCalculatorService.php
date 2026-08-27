<?php

namespace App\Services;

use App\Models\Vehicle;
use App\Models\ShippingRate;

class FleetCalculatorService
{
    /**
     * Hitung kebutuhan armada & total ongkir berdasarkan Tonase (Kg), Kubikasi (m3), dan Kota Tujuan.
     *
     * @param float $totalWeightKg  Total berat barang dalam Kilogram (kg)
     * @param float $totalVolumeM3 Total volume barang dalam Meter Kubik (m3)
     * @param string $kota          Nama kota tujuan pengiriman
     * @param float|null $jarakKm   (Opsional) Jarak KM untuk pengembangan masa depan
     * @return array
     */
    public function calculate(float $totalWeightKg, float $totalVolumeM3, string $kota, ?float $jarakKm = null): array
    {
        $kota = strtoupper(trim($kota));

        if ($totalWeightKg <= 0 && $totalVolumeM3 <= 0) {
            return [
                'success' => false,
                'message' => 'Total berat dan volume barang tidak boleh kosong.'
            ];
        }

        // =========================================================================
        // SKENARIO 1: SINGLE LOAD (1 Truk Cukup)
        // Cari 1 armada terkecil (berdasarkan urutan/kapasitas) yang muat menampung beban
        // =========================================================================
        $singleVehicle = Vehicle::where('is_aktif', true)
            ->where('max_berat_kg', '>=', $totalWeightKg)
            ->where('max_volume_m3', '>=', $totalVolumeM3)
            ->orderBy('urutan', 'asc')
            ->orderBy('max_berat_kg', 'asc')
            ->first();

        if ($singleVehicle) {
            $cost = $this->getRate($singleVehicle->id, $kota, $jarakKm);

            if ($cost > 0) {
                return [
                    'success'      => true,
                    'is_split'     => false,
                    'total_cost'   => $cost,
                    'total_weight' => $totalWeightKg,
                    'total_volume' => $totalVolumeM3,
                    'fleet'        => [
                        [
                            'vehicle_id'   => $singleVehicle->id,
                            'vehicle_name' => $singleVehicle->nama_armada,
                            'qty'          => 1,
                            'unit_cost'    => $cost,
                            'subtotal'     => $cost
                        ]
                    ]
                ];
            }
        }

        // =========================================================================
        // SKENARIO 2: SPLIT LOAD / MULTI-ARMADA (Beban melebih kapasitas 1 armada)
        // Algoritma kombinasi armada terbesar -> terkecil hingga seluruh muatan tercover
        // =========================================================================
        $availableVehicles = Vehicle::where('is_aktif', true)
            ->orderBy('urutan', 'desc')
            ->orderBy('max_berat_kg', 'desc')
            ->get();

        if ($availableVehicles->isEmpty()) {
            return [
                'success' => false,
                'message' => 'Belum ada data armada aktif di sistem.'
            ];
        }

        $remWeight = $totalWeightKg;
        $remVolume = $totalVolumeM3;
        $selectedFleet = [];
        $totalCost = 0;

        while ($remWeight > 0 || $remVolume > 0) {
            $bestVehicle = null;

            // Cari armada terbesar yang terisi efisien (minimal 40% dari kapasitasnya)
            foreach ($availableVehicles as $vehicle) {
                $effWeight = $remWeight >= ($vehicle->max_berat_kg * 0.4);
                $effVolume = $remVolume >= ($vehicle->max_volume_m3 * 0.4);

                if ($effWeight || $effVolume) {
                    $bestVehicle = $vehicle;
                    break;
                }
            }

            // Jika sisa muatan kecil (<40% armada terbesar), pilih armada terkecil yang cukup menampung sisa
            if (!$bestVehicle) {
                $bestVehicle = Vehicle::where('is_aktif', true)
                    ->where('max_berat_kg', '>=', $remWeight)
                    ->where('max_volume_m3', '>=', $remVolume)
                    ->orderBy('urutan', 'asc')
                    ->first();

                // Fallback terakhir: jika tidak ada yang pas, ambil armada terkecil yang aktif
                if (!$bestVehicle) {
                    $bestVehicle = $availableVehicles->last();
                }
            }

            // Ambil tarif ongkir armada terpilih untuk kota tujuan
            $rate = $this->getRate($bestVehicle->id, $kota, $jarakKm);

            if ($rate <= 0) {
                return [
                    'success' => false,
                    'message' => "Armada '{$bestVehicle->nama_armada}' belum memiliki tarif aktif untuk kota {$kota}."
                ];
            }

            // Kurangi sisa muatan
            $remWeight -= $bestVehicle->max_berat_kg;
            $remVolume -= $bestVehicle->max_volume_m3;
            $totalCost += $rate;

            // Kelompokkan jumlah armada yang sama
            $vId = $bestVehicle->id;
            if (isset($selectedFleet[$vId])) {
                $selectedFleet[$vId]['qty'] += 1;
                $selectedFleet[$vId]['subtotal'] += $rate;
            } else {
                $selectedFleet[$vId] = [
                    'vehicle_id'   => $bestVehicle->id,
                    'vehicle_name' => $bestVehicle->nama_armada,
                    'qty'          => 1,
                    'unit_cost'    => $rate,
                    'subtotal'     => $rate
                ];
            }
        }

        return [
            'success'      => true,
            'is_split'     => true,
            'total_cost'   => $totalCost,
            'total_weight' => $totalWeightKg,
            'total_volume' => $totalVolumeM3,
            'fleet'        => array_values($selectedFleet)
        ];
    }

    /**
     * Helper privat untuk mengambil nilai tarif dari tabel shipping_rates
     */
    private function getRate(int $vehicleId, string $kota, ?float $jarakKm = null): float
    {
        $rate = ShippingRate::where('vehicle_id', $vehicleId)
            ->where('kota', $kota)
            ->where('is_aktif', true)
            ->first();

        if (!$rate) {
            return 0;
        }

        // Jika di masa depan per-KM diaktifkan dan jarak diisi
        if ($jarakKm && $rate->biaya_per_km > 0) {
            return (float) ($rate->biaya_per_km * $jarakKm);
        }

        return (float) $rate->biaya_pengiriman;
    }
}