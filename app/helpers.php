<?php

if (!function_exists('asset_versioned')) {
    /**
     * Generate an asset path with automatic filemtime cache-busting version query.
     * Mencegah browser menyajikan CSS/JS versi lama setelah perubahan disimpan.
     *
     * @param string $path
     * @param bool|null $secure
     * @return string
     */
    function asset_versioned(string $path, ?bool $secure = null): string
    {
        $realPath = public_path($path);
        if (file_exists($realPath)) {
            $mtime = filemtime($realPath);
            return asset($path, $secure) . '?v=' . $mtime;
        }

        return asset($path, $secure);
    }
}
