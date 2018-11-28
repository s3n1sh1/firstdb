<?php

namespace App\Http\Middleware;

use Closure;
use Exception;

class DecryptMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if($request->has('ciphertext')){
            try {
                $iv  = hex2bin($request->iv);
            } catch (Exception $e) { return null; }

            $ciphertext = base64_decode($request->ciphertext);
            $key = hash('sha256', env('SECRET'));

            $param = openssl_decrypt($ciphertext , 'aes-256-cbc', hex2bin($key), OPENSSL_RAW_DATA, $iv);
            $request->request->replace((array)json_decode($param));

            return $next($request);
        }
        return $next($request);
    }
}
