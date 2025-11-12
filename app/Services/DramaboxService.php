<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use App\Interfaces\MovieServiceInterface;


class DramaboxService  implements MovieServiceInterface
{


   public function fetchTheater()
{
    $ip = request()->ip();
    $lang = request()->lang ?? 'in';
    $cacheKey = 'dramabox_theaters_' . sha1($ip . date('Y-m-d_H'));
    if (Cache::has($cacheKey)) {
        return Cache::get($cacheKey);
    }
    $init = $this->init();
    if (is_array($init) && ($response['success'] ?? false) === true) {
        Cache::put($cacheKey, $init, now()->addMinutes(15));
    }

    $cacheKeyTheater = 'dramabox_theaters_data_'.sha1($ip.date('d-m-Y').$lang);
    if(Cache::has($cacheKeyTheater))
    {
        $res = Cache::get($cacheKeyTheater);
        return response()->json($res,200,[],JSON_PRETTY_PRINT)->getData(true);
    }
    $data= $this->getTheather($init['token'], $lang);
    
    if(is_array($data) && ($data['success'] ?? false) === true)
    {
            $collect = collect($data['data']['columnVoList']);
            $datax = $collect->mapWithKeys(function($item){
                $key = Str::snake(strtolower($item['title'] ?? 'unknown'));
                return [$key => $item['bookList'] ?? []];
            })->toArray();
        $r['success'] = true;
        $r['message'] = 'sucess';
        $r['data'] = $datax;
        Cache::put($cacheKeyTheater , $r,now()->addDay());
        return response()->json($r,200,[],JSON_PRETTY_PRINT)->getData(true);
    }else{
        $r['success'] = false;
        $r['message'] = 'Failed';
        return response()->json($r,200,[],JSON_PRETTY_PRINT)->getData(true);
    }

}


    public function fetchChapters()
    {
        // Implementation for fetching chapters
    }

    public function fetchTheaterDetails($theaterId)
    {
        // Implementation for fetching theater details by ID
    }


    private function getTheather($token,$lang='in')
    {
        $client = new \GuzzleHttp\Client([
            'proxy' => \App\Helpers\Dramabox::getProxies(),
            'verify' => false, // opsional kalau HTTPS error
        ]);

        $payload = [
            'isNeedRank' => 1,
            'isNeedNewChannel' => 1,
            'index' => 0,
            'type' => 0,
            'channelId' => 155
        ];
        $deviceId = (string) str()->uuid();
        
        $headers = \App\Helpers\Dramabox::generateRandomHeaders($lang,$token,$deviceId , \App\Helpers\Dramabox::generateRandomAndroidId() ,json_encode($payload));

        $response = $client->request('POST','https://sapi.dramaboxdb.com/drama-box/he001/theater?timestamp='.(now()->timestamp * 1000) , [
            'headers' => $headers,
            'body' => json_encode($payload)
        ]);

        return json_decode($response->getBody()->getContents() ,true);
    }
    private function init()
    {

        $client = new \GuzzleHttp\Client([
            'proxy' => \App\Helpers\Dramabox::getProxies(),
            'verify' => false, // opsional kalau HTTPS error
        ]);

        $response = $client->request('POST', 'https://sapi.dramaboxdb.com/drama-box/ap001/bootstrap?timestamps=1762833217803', [
            'headers' => [
                'host' => 'sapi.dramaboxdb.com',
                'over-flow' => 'new-fly',
                'vn' => '4.7.0',
                'time-zone' => '+0700',
                'accept' => '*/*',
                'local-time' => '2025-11-11 10:53:37.804 +0700',
                'mcc' => '65535',
                'locale' => 'id_ID',
                'pline' => 'IOS',
                'apn' => '1',
                'mchid' => '',
                'user-agent' => 'DramaBox/4.7.0 (com.storymatrix.drama; build:407000; iOS 18.6.2) Alamofire/5.9.1',
                'version' => '407000',
                'is_vpn' => '1',
                'eighteen-bans' => '0',
                'brand' => 'apple',
                'md' => 'iPhone12,3',
                'mbid' => '',
                'instanceid' => '7A3F2013141443CE9C0504E4D505A9F2',
                'accept-language' => 'id-ID;q=1.0',
                'p' => '48',
                'mf' => 'Apple',
                'package-name' => 'com.storymatrix.drama',
                'current-language' => 'in',
                'idfv' => '38302440-B168-4BAC-86E3-21EEFBB710F4',
                'cid' => 'DRI1000000',
                'adid' => '',
                'afid' => '1762833157986-3830244',
                'idfa' => '',
                'srn' => '1125*2436',
                'tz' => '-420',
                'lat' => '0',
                'sn' => 'B+PTP/upoPaVAnCO0PrnbqGtx8/nhzVGmEAPx3f+DMKwokgVyLLbbHxEWzdlvnCexpewdoxqCu2CgrCdnlylNtHBFVwpQWx9EspYanrencwd4nHQLLvbTUS+D9b8xYGr4ypBaSynSDpv076teAT1yU9PAASx9HdijsF5ncyvKeKIOYAKgPyDrwMV1EUPPyduIaD6G2YyRfgGQ5y2ogKld47SYPIDVA2l8EaENvcap09zXLhGBU45imU6ey9cKOzJRmuf2xanttexPujuqmWZOytFWIeWycRaR1j4m++PTXeY0X6TNwlsArgvRip/AoVDzSG6+iM8r9em+8lfG8cJNg==',
                'connection' => 'keep-alive',
                'build' => 'iPhone12,3',
                'is_root' => '0',
                'ins' => '1762833217669',
                'language' => 'id',
                'device-id' => '38302440b1684bac86e321eefbb710f4',
                'ov' => '18.6.2',
                'active-time' => '178',
                'is_emulator' => '0',
                'content-type' => 'application/json',
            ],
            'body' => '{}',
        ]);

        $resp = json_decode($response->getBody()->getContents(), true);

        if ($resp['success']) {
            $ret['success'] = true;
            $ret['token'] = $resp['data']['user']['token'];
            return $ret;
        } else {
            $ret['success'] = false;
            $ret['token'] = null;
            return $ret;
        }
    }
}
