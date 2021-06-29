<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\CreateConversionTrigger;
use Illuminate\Support\Facades\Log;


class ProductController extends Controller
{
    public function newShopifyProduct(){
        Log::debug("Processing Shopify product");
        $data = \Request::get('jsonData');
        $dataArray = json_decode($data, 1);
        
        foreach ($dataArray['variants'] as $key => $value) {
            if(strpos($value['sku'],'rfsnadid')) {
                Log::debug("SKU added to queue");
            }
            CreateConversionTrigger::dispatchIf(strpos($value['sku'],'rfsnadid'), $value['sku']);
        }
        //Send 202 response since we are putting the job in a queue
        http_response_code(202);
    }
}
