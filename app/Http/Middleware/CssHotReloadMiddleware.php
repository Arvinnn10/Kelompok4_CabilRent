<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CssHotReloadMiddleware
{
    /**
     * Handle an incoming request.
     * Menginjeksi client script auto CSS hot reload secara otomatis ke dokumen HTML
     * khusus pada lingkungan local development.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Hanya aktif di environment local / testing untuk response HTML biasa (bukan AJAX/API)
        if (app()->environment('local', 'testing')
            && $request->isMethod('GET')
            && !$request->ajax()
            && !$request->wantsJson()
            && $response instanceof \Illuminate\Http\Response
            && str_contains($response->headers->get('Content-Type', ''), 'text/html')
        ) {
            $content = $response->getContent();
            if ($content && str_contains($content, '</body>')) {
                $scriptUrl = asset_versioned('js/css-hot-reload.js');
                $endpointUrl = url('/_css-dev/status');
                $scriptTag = "\n<!-- [CabilRent Dev] Automatic CSS Hot Reload -->\n"
                    . "<script src=\"{$scriptUrl}\" data-dev-endpoint=\"{$endpointUrl}\" defer></script>\n"
                    . "</body>";
                $content = str_replace('</body>', $scriptTag, $content);
                $response->setContent($content);
            }
        }

        return $response;
    }
}
