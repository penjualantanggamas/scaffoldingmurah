<?php

namespace App\Http\Controllers;

use App\Models\ShippingRate;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class AdminShippingRateController extends Controller
{
    public function index()
    {
        $shippingRates = ShippingRate::with('vehicle')->latest()->get();
        $vehicles = Vehicle::where('is_aktif', true)->orderBy('urutan', 'asc')->get();

        return view('admin.shipping_rates.index', compact('shippingRates', 'vehicles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'provinsi'         => 'required|string|max:255',
            'kota'             => 'required|string|max:255',
            'vehicle_id'       => 'required|exists:vehicles,id',
            'biaya_pengiriman' => 'required|numeric|min:0',
        ]);

        // Mencegah duplikasi kota untuk jenis armada yang sama
        $exists = ShippingRate::where('kota', strtoupper(trim($request->kota)))
            ->where('vehicle_id', $request->vehicle_id)
            ->exists();

        if ($exists) {
            return back()->withErrors(['kota' => 'Tarif untuk kota dan jenis armada tersebut sudah terdaftar.']);
        }

        ShippingRate::create([
            'provinsi'         => strtoupper(trim($request->provinsi)),
            'kota'             => strtoupper(trim($request->kota)),
            'vehicle_id'       => $request->vehicle_id,
            'biaya_pengiriman' => $request->biaya_pengiriman,
            'is_aktif'         => true,
        ]);

        return back()->with('success', 'Wilayah & tarif armada berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $rate = ShippingRate::findOrFail($id);

        $request->validate([
            'provinsi'         => 'required|string|max:255',
            'kota'             => 'required|string|max:255',
            'vehicle_id'       => 'required|exists:vehicles,id',
            'biaya_pengiriman' => 'required|numeric|min:0',
        ]);

        $rate->update([
            'provinsi'         => strtoupper(trim($request->provinsi)),
            'kota'             => strtoupper(trim($request->kota)),
            'vehicle_id'       => $request->vehicle_id,
            'biaya_pengiriman' => $request->biaya_pengiriman,
        ]);

        return back()->with('success', 'Tarif ongkir berhasil diperbarui!');
    }

    public function toggleStatus($id)
    {
        $rate = ShippingRate::findOrFail($id);
        $rate->is_aktif = !$rate->is_aktif;
        $rate->save();

        return back()->with('success', 'Status tarif berhasil diubah!');
    }

    public function destroy($id)
    {
        $rate = ShippingRate::findOrFail($id);
        $rate->delete();

        return back()->with('success', 'Tarif wilayah berhasil dihapus!');
    }
}