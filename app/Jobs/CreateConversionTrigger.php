<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CreateConversionTrigger implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $trigger;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($trigger)
    {
        $this->trigger = $trigger;
    }

    /**
     * create request parameter for creating new affiliate trigger
     * @param string $trigger = rfsnadid:{affiliateID}
     * 
     * @return void
     * 
     */
    public function handle()
    {
        $trigger = $this->trigger;
        $url = 'https://www.refersion.com/apihttps://www.refersion.com/api/new_affiliate_trigger';
        $response = Http::withHeaders([
            'refersion-public-key' => env('REFERSION_PUBLIC_KEY'),
            'refersion-secret-key' => env('REFERSION_SECRET_KEY'),
            'content-type' => 'application/json'
        ])->post($url, [
            "affiliate_code" => explode("rfsnadid:",$trigger)[1],
            "type" => "SKU",
            "trigger" => $trigger,
        ]);
    }
}