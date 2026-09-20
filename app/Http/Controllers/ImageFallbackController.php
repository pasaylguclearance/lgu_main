<?php

namespace App\Http\Controllers;

/**
 * Serves a placeholder for any image under /img or /backend/img that does not
 * exist on disk (applicant photos, signatures and fingerprints live outside the
 * repository). Apache serves real files directly; only missing ones reach here.
 */
class ImageFallbackController extends Controller
{
    public function show($path)
    {
        return response()->file(public_path('img/placeholder/'.$this->placeholderFor($path).'.svg'), [
            'Content-Type'  => 'image/svg+xml',
            // Never cache the fallback: once the real file is uploaded the
            // browser must fetch it instead of the placeholder.
            'Cache-Control' => 'no-store, max-age=0',
        ]);
    }

    private function placeholderFor($path)
    {
        if (preg_match('#^application_picture/|sample-img\.#', $path)) {
            return 'photo';
        }
        if (preg_match('#^signature/|sample-signature\.#', $path)) {
            return 'signature';
        }
        if (preg_match('#^fingerprint_(left|right)/|fingerprint-default\.|thumb-right\.#', $path)) {
            return 'fingerprint';
        }
        return 'image';
    }
}
