<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    /**
     * Menangani unduhan file materi yang dilindungi.
     *
     * @param Material $material
     * @return \Symfony\Component\HttpFoundation\StreamedResponse|\Illuminate\Http\Response
     */
    public function downloadMaterial(Material $material)
    {
        // Pastikan file yang diminta benar-benar ada di disk 'private'
        if (!Storage::disk('private')->exists($material->file_path)) {
            abort(404, 'File tidak ditemukan.');
        }

        // Dapatkan path lengkap ke file
        $path = Storage::disk('private')->path($material->file_path);

        // Dapatkan nama asli file untuk ditampilkan saat diunduh
        $fileName = basename($path);

        // Gunakan response()->download() untuk menyajikan file kepada pengguna
        // Ini akan secara otomatis mengatur header yang diperlukan
        return response()->download($path, $fileName);
    }

}
