<?php

namespace App\Services\Core;

use App\Helpers\Netshort;
use App\Traits\MovieServiceTrait;
use Exception;
use Illuminate\Support\Facades\Http;

class NetshortCore
{
    use MovieServiceTrait;
    public $lang;
    public function __construct()
    {
        $this->lang = 'in';
        $init = $this->bootstrap();
        Netshort::$token = $init['token'];

    }

    public function fetchTheaters() {
        $headers = Netshort::generateRandomHeaders($this->lang);
        $data = Netshort::generatePayload([
            'tabId' => null
        ]);
        $headers['encrypt-key'] = $data['key'];
      
        try{
            $response = $this->customRequest($headers)->withBody($data['data'])->post('https://appsecapi.netshort.com/prod-app-api/video/shortPlay/tab/load_group_tabId');

            if($response->successful())
            {
                $decrypted = Netshort::decryptBodyResponse($response);
                return $decrypted;
            }else{
                return 'xxx';
            }
        }catch(Exception $e)
        {
            return 'error';
        }
    }

    public function bootstrap()
    {

        $headers = Netshort::generateRandomHeaders('in');
        $data = Netshort::generatePayload([
            'accessToken' => '',
            'appVer' => '1.6.8',
            'deviceCode' => Netshort::generateRandomString(16),
            'deviceId' => Netshort::generateDeviceId(),
            'email' => '',
            'emailCode' => '',
            'model' => Netshort::generateRandomModel(),
            'os' => 'Android',
            'osVer' => rand(12, 15),
            'socialState' => Netshort::generateRandomString(32),
            'source' => 'visitor',
        ]);
        $headers['encrypt-key'] = $data['key'];

       
        $response = $this->customRequest($headers)
            ->withBody($data['data'])
            ->post('https://appsecapi.netshort.com/prod-app-api/auth/login');

        if ($response->successful()) {
            $decrypted = Netshort::decryptBodyResponse($response);
            Netshort::$token = $decrypted['data']['token'];
            return $decrypted['data'];
        } else {
            return null;
        }
    }
    private function customRequest($headers)
    {
        $response = Http::withHeaders($headers);
        $opts = [
            'proxy' => $this->getProxies()
        ];
        $response = $response->withOptions($opts);

        return $response;
    }
}
