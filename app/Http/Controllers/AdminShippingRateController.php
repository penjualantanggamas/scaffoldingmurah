<?php

namespace App\Http\Controllers;

use App\Models\ShippingRate;
use Illuminate\Http\Request;

class AdminShippingRateController extends Controller
{
    /**
     * Menampilkan daftar tarif ongkir Armada Gudang
     */
    public function index()
    {
        $shippingRates = ShippingRate::latest()->get();
        return view('admin.shipping_rates.index', compact('shippingRates'));
    }

    /**
     * Menyimpan wilayah jangkauan & tarif baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'provinsi'         => 'required|string|max:255',
            'kota'             => 'required|string|max:255|unique:shipping_rates,kota',
            'biaya_pengiriman' => 'required|numeric|min:0',
        ]);

        ShippingRate::create([
            'provinsi'         => strtoupper(trim($request->provinsi)),
            'kota'             => strtoupper(trim($request->kota)),
            'biaya_pengiriman' => $request->biaya_pengiriman,
            'is_aktif'         => true,
        ]);

        return back()->with('success', 'Wilayah jangkauan & tarif Armada Gudang berhasil ditambahkan!');
    }

    /**
     * Memperbarui tarif ongkir
     */
    public function update(Request $request, $id)
    {
        $rate = ShippingRate::findOrFail($id);

        $request->validate([
            'provinsi'         => 'required|string|max:255',
            'kota'             => 'required|string|max:255|unique:shipping_rates,kota,' . $id,
            'biaya_pengiriman' => 'required|numeric|min:0',
        ]);

        $rate->update([
            'provinsi'         => strtoupper(trim($request->provinsi)),
            'kota'             => strtoupper(trim($request->kota)),
            'biaya_pengiriman' => $request->biaya_pengiriman,
        ]);

        return back()->with('success', 'Tarif ongkir wilayah berhasil diperbarui!');
    }

    /**
     * Toggle status aktif / nonaktifkan jangkauan wilayah
     */
    public function toggleStatus($id)
    {
        $rate = ShippingRate::findOrFail($id);
        $rate->is_aktif = !$rate->is_aktif;
        $rate->save();

        return back()->with('success', 'Status jangkauan wilayah berhasil diubah!');
    }

    /**
     * Menghapus wilayah jangkauan
     */
    public function destroy($id)
    {
        $rate = ShippingRate::findOrFail($id);
        $rate->delete();

        return back()->with('success', 'Wilayah jangkauan berhasil dihapus!');
    }
}