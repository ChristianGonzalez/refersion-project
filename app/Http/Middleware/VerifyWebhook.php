<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;


class VerifyWebhook
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
        Log::debug("Verifying shopify webhooks");
        $hmac_header =  $_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256'];

        $data = file_get_contents('php://input');

        if(! $this -> verify_webhook($data, $hmac_header)){
            die;
        }
        
        // Adds Passed Data in request
        $request->attributes->add(['jsonData' => $data]); 
        return $next($request);
    }

    public function verify_webhook($data, $hmac_header)
    {
      $calculated_hmac = base64_encode(hash_hmac('sha256', $data, env('SHOPIFY_WEBHOOK_SECRET_KEY'), true));
      return hash_equals($hmac_header, $calculated_hmac);
    }
}
