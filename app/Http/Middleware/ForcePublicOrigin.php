<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class ForcePublicOrigin
{
    public function handle(Request $request, Closure $next): Response
    {
        $publicOrigin = $this->resolvePublicOrigin($request);

        if ($publicOrigin !== null) {
            URL::forceRootUrl($publicOrigin);

            $scheme = parse_url($publicOrigin, PHP_URL_SCHEME);

            if (is_string($scheme) && $scheme !== '') {
                URL::forceScheme($scheme);
            }
        }

        return $next($request);
    }

    private function resolvePublicOrigin(Request $request): ?string
    {
        $forwardedHost = $this->firstHeaderValue($request->headers->get('x-forwarded-host'));

        if ($forwardedHost !== null) {
            return 'https://'.$forwardedHost;
        }

        $appUrl = config('app.url');

        if (is_string($appUrl) && $appUrl !== '') {
            return rtrim($appUrl, '/');
        }

        $requestHost = $request->getHost();

        if ($requestHost === '') {
            return null;
        }

        return $request->getSchemeAndHttpHost();
    }

    private function firstHeaderValue(?string $value): ?string
    {
        if (! is_string($value) || trim($value) === '') {
            return null;
        }

        $parts = array_map('trim', explode(',', $value));

        return $parts[0] !== '' ? $parts[0] : null;
    }
}
