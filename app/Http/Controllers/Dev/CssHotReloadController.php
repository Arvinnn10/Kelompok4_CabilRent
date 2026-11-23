<?php

namespace App\Http\Controllers\Dev;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\File;

class CssHotReloadController extends Controller
{
    /**
     * Mengembalikan status timestamp (mtime) seluruh file CSS di public/css/
     * Hanya aktif di lingkungan development (APP_ENV=local).
     */
    public function status(): JsonResponse
    {
        if (!app()->environment('local', 'testing')) {
            abort(404, 'Endpoint hanya tersedia pada environment development.');
        }

        $cssDir = public_path('css');
        $files = [];

        if (File::isDirectory($cssDir)) {
            foreach (File::files($cssDir) as $file) {
                if ($file->getExtension() === 'css') {
                    $files[$file->getFilename()] = $file->getMTime();
                }
            }
        }

        return response()->json([
            'success'   => true,
            'timestamp' => microtime(true),
            'files'     => $files,
        ])->header('Cache-Control', 'no-cache, no-store, must-revalidate')
          ->header('Pragma', 'no-cache')
          ->header('Expires', '0');
    }
}
