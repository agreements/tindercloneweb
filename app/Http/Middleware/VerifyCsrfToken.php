<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;
use Closure;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Cookie;
use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Session\TokenMismatchException;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        '/pages/upload/image',
        '/plugin/upload/email/image',
        "testing/*",
    	"paypal",
    	"charge",
        "paymentwall_charge",
        "credits_callback",
        "superpower_callback",
    	"superpower_charge"
    ];


    public function handle($request, Closure $next)
    {

        $except_routes = \App\Components\Plugin::getCSRFRemoveRoutes();
        $this->except = array_merge($this->except, $except_routes);


        if ($this->isReading($request) || $this->shouldPassThrough($request) || $this->tokensMatch($request)) {
            return $this->addCookieToResponse($request, $next($request));
        }

        throw new TokenMismatchException;
    }


}
