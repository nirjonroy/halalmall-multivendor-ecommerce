<?php

namespace App\Http\Controllers\Seller;

use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\Model\BusinessSetting;
use App\Model\SocialMedia;
use Brian2694\Toastr\Facades\Toastr;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use phpseclib3\Crypt\RSA\Formats\Keys\JWK;

class BusinessSettingsController extends Controller
{
    public function seller_analytics_update(Request $request) {
       DB::table('business_settings')->updateOrInsert(['type' => 'seller_pixel_analytics'], [
            'value' => $request['seller_pixel_analytics']
        ]);

        Toastr::success(\App\CPU\translate('config_data_updated'));
        return back();
    }
}