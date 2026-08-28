<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function store(Request $request)
    {
        try {
            if ($request->hasFile('upload')) {
                // Validasi file
                $request->validate([
                    'upload' => 'required|image|mimes:jpeg,png,jpg,webp,gif|max:10240',
                ]);

                // Simpan ke storage/app/public/uploads
                $path = $request->file('upload')->store('uploads', 'public');

                // Return format JSON standar CKEditor 5
                return response()->json([
                    'url' => asset('storage/' . $path)
                ]);
            }

            return response()->json([
                'error' => [
                    'message' => 'File tidak ditemukan.'
                ]
            ], 400);

        } catch (\Exception $e) {
            // Tangkap error validasi atau server dan kirim ke CKEditor
            return response()->json([
                'error' => [
                    'message' => $e->getMessage()
                ]
            ], 400);
        }
    }
}