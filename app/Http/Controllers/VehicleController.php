<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function index()
    {
        $vehicles = Vehicle::orderBy('urutan', 'asc')->get();
        return view('admin.vehicles.index', compact('vehicles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_armada'   => 'required|string|max:255',
            'max_berat_kg'  => 'required|numeric|min:0',
            'max_volume_m3' => 'required|numeric|min:0',
            'urutan'        => 'required|integer|min:1',
        ]);

        Vehicle::create([
            'nama_armada'   => trim($request->nama_armada),
            'max_berat_kg'  => $request->max_berat_kg,
            'max_volume_m3' => $request->max_volume_m3,
            'urutan'        => $request->urutan,
            'is_aktif'      => true,
        ]);

        return back()->with('success', 'Armada baru berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $vehicle = Vehicle::findOrFail($id);

        $request->validate([
            'nama_armada'   => 'required|string|max:255',
            'max_berat_kg'  => 'required|numeric|min:0',
            'max_volume_m3' => 'required|numeric|min:0',
            'urutan'        => 'required|integer|min:1',
        ]);

        $vehicle->update([
            'nama_armada'   => trim($request->nama_armada),
            'max_berat_kg'  => $request->max_berat_kg,
            'max_volume_m3' => $request->max_volume_m3,
            'urutan'        => $request->urutan,
        ]);

        return back()->with('success', 'Data armada berhasil diperbarui!');
    }

    public function toggleStatus($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        $vehicle->is_aktif = !$vehicle->is_aktif;
        $vehicle->save();

        return back()->with('success', 'Status aktif armada berhasil diubah!');
    }

    public function destroy($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        $vehicle->delete();

        return back()->with('success', 'Armada berhasil dihapus!');
    }
}