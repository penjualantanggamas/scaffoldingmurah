<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $modeTransaksi = AppSetting::getValue('mode_transaksi', 'web'); // default: 'web' atau 'whatsapp'
        $whatsappAdmin = AppSetting::getValue('whatsapp_admin', '6281234567890');

        return view('admin.settings.transaction', compact('modeTransaksi', 'whatsappAdmin'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'whatsapp_admin' => 'required|numeric',
        ]);

        // Simpan mode: 'web' jika checkbox diaktifkan, 'whatsapp' jika dimatikan
        $mode = $request->has('mode_transaksi') ? 'web' : 'whatsapp';

        AppSetting::setValue('mode_transaksi', $mode);
        AppSetting::setValue('whatsapp_admin', preg_replace('/[^0-9]/', '', $request->whatsapp_admin));

        return back()->with('success', 'Pengaturan mode transaksi berhasil diperbarui!');
    }
}