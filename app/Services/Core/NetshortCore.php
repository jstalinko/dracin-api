<?php

namespace App\Services\Core;

use Exception;
use App\Helpers\Netshort;
use App\Traits\MovieServiceTrait;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class NetshortCore
{
    use MovieServiceTrait;
    public $lang;
    public $pageNo;

    
    public function __construct()
    {
        $this->lang = 'in';
        $this->pageNo = 1;
        $ttl_minutes = 5 * 60;

        // Nama kunci yang digunakan di cache
        $cache_key = 'nets_token';
        // Gunakan Cache::remember() untuk mendapatkan token
        $token = Cache::remember($cache_key, $ttl_minutes, function () {
            $init = $this->bootstrap();
            return $init['token'];
        });

        Netshort::$token = $token;
    }

    public function fetchTheaters()
    {
        $headers = Netshort::generateRandomHeaders($this->lang);
        $data = Netshort::generatePayload([
            'tabId' => null
        ]);
        $headers['encrypt-key'] = $data['key'];

        try {
            $response = $this->customRequest($headers)->withBody($data['data'])->post('https://appsecapi.netshort.com/prod-app-api/video/shortPlay/tab/load_group_tabId');

            if ($response->successful()) {
                $decrypted = Netshort::decryptBodyResponse($response);
                return $decrypted;
            } else {
                return [];
            }
        } catch (Exception $e) {
            return ['error' => $e];
        }
    }
    public function fetchCategory()
    {
        //
    }
    public function fetchDetail($bookId)
    {
        
        $headers = Netshort::generateRandomHeaders($this->lang);
        $data = Netshort::generatePayload([
            'codec' => '',
            'playClarity' => '1080p',
            'shortPlayId' => $bookId,
        ]);
        $headers['encrypt-key'] = $data['key'];

        try {
            $response = $this->customRequest($headers)->withBody($data['data'])->post('https://appsecapi.netshort.com/prod-app-api/video/shortPlay/base/detail_info');

            if ($response->successful()) {
                $decrypted = Netshort::decryptBodyResponse($response);
                return $decrypted;
            } else {
                return [];
            }
        } catch (Exception $e) {
            return ['error' => $e];
        }
    }

    public function fetchStream($bookId)
    {
        //
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
    public function fetchSearch($query) {
        $page = $this->pageNo ?? 1;
             $headers = Netshort::generateRandomHeaders($this->lang);
        $data = Netshort::generatePayload([
            'id' => 0,
            'shortPlayId' => 0,
            'searchCode' => $query,
            'pageNo' => $page,
            'pageSize' => 10,
            'searchFlag' => 1,
        ]);
        $headers['encrypt-key'] = $data['key'];
           $response = $this->customRequest($headers)
            ->withBody($data['data'])
            ->post('https://appsecapi.netshort.com/prod-app-api/video/shortPlay/search/searchByKeyword');

        if ($response->successful()) {
            $decrypted = Netshort::decryptBodyResponse($response);
            return $decrypted;
        } else {
            return null;
        }
        
    }
    public function fetchRecommend($bookId) {
       //  dd($bookId);
        $headers = Netshort::generateRandomHeaders($this->lang);
        $data = Netshort::generatePayload([
           'shortPlayId' => $bookId,
        ]);
        $headers['encrypt-key'] = $data['key'];

        try {
            $response = $this->customRequest($headers)->withBody($data['data'])->post('https://appsecapi.netshort.com/prod-app-api/video/shortPlay/recommend/loadBackRecommend');

            if ($response->successful()) {
                $decrypted = Netshort::decryptBodyResponse($response);
                return $decrypted;
            } else {
                return [];
            }
        } catch (Exception $e) {
            return ['error' => $e];
        }   
    }

    public function fetchRecommendAll()
    {
        $page = (int) $this->pageNo ?? 1;
        if($page < 1)
        {
            $page =1;
        }
        $limit = 20;
        $offset = ($page-1)*$limit+1;
         $headers = Netshort::generateRandomHeaders($this->lang);
        $data = Netshort::generatePayload([
             'tabId' => null,
            'offset' => $offset,
            'limit' => $limit,
        ]);
        $headers['encrypt-key'] = $data['key'];

        try {
            $response = $this->customRequest($headers)->withBody($data['data'])->post('https://appsecapi.netshort.com/prod-app-api/video/shortPlay/tab/load_all_group_tabId');

            if ($response->successful()) {
                $decrypted = Netshort::decryptBodyResponse($response);
                return $decrypted;
            } else {
                return [];
            }
        } catch (Exception $e) {
            return ['error' => $e];
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
