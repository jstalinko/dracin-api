<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use App\Interfaces\MovieServiceInterface;


class DramaboxService  implements MovieServiceInterface
{


    public function fetchTheater()
    {
        $ip = request()->ip();
        $lang = request()->lang ?? 'in';
        $cacheKey = 'dramabox_theaters_' . sha1(date('Y-m-d_H'));
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }
        $init = $this->init();
        if (is_array($init) && ($response['success'] ?? false) === true) {
            Cache::put($cacheKey, $init, now()->addMinutes(15));
        }

        $cacheKeyTheater = 'dramabox_theaters_data_' . sha1(date('d-m-Y') . $lang);
        if (Cache::has($cacheKeyTheater)) {
            $res = Cache::get($cacheKeyTheater);
            return response()->json($res, 200, [], JSON_PRETTY_PRINT)->getData(true);
        }
        $data = $this->getTheather($init['token'], $lang);

        if (is_array($data) && ($data['success'] ?? false) === true) {
            $collect = collect($data['data']['columnVoList']);
            $datax = $collect->mapWithKeys(function ($item) {
                $key = Str::snake(strtolower($item['title'] ?? 'unknown'));
                return [$key => $item['bookList'] ?? []];
            })->toArray();
            $r['success'] = true;
            $r['message'] = 'sucess';
            $r['data'] = $datax;
            Cache::put($cacheKeyTheater, $r, now()->addDay());
            return response()->json($r, 200, [], JSON_PRETTY_PRINT)->getData(true);
        } else {
            $r['success'] = false;
            $r['message'] = 'Failed';
            return response()->json($r, 200, [], JSON_PRETTY_PRINT)->getData(true);
        }
    }

    public function fetchRecommend($pageNo)
    {
        $ip = request()->ip();
        $lang = request()->lang ?? 'in';

        $cacheKey = 'dramabox_theaters_' . sha1(date('Y-m-d_H'));
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }
        $init = $this->init();
        if (is_array($init) && ($response['success'] ?? false) === true) {
            Cache::put($cacheKey, $init, now()->addMinutes(15));
        }
        $init = $this->init();

        if (is_array($init) && ($response['success'] ?? false) === true) {
            Cache::put($cacheKey, $init, now()->addMinutes(15));
        }

        $cacheKeyRec = 'dramabox_recommend_' . sha1(date('Y-m-d H'));

        if (Cache::has($cacheKeyRec)) {
            $res = Cache::get($cacheKeyRec);
            return response()->json($res, 200, [], JSON_PRETTY_PRINT);
        }

        $data = $this->getRecommend($init['token'], $pageNo, $lang);


        if (is_array($data) && ($data['success'] ?? false) === true) {
            $r['success'] = true;
            $r['message'] = 'success';
            $r['data'] = $data['data']['recommendList'];
            Cache::put($cacheKeyRec, $r, now()->addDay());
            return response()->json($r, 200, [], JSON_PRETTY_PRINT);
        } else {
            $r['success'] = false;
            $r['message'] = 'failed';

            return response()->json($r, 200, [], JSON_PRETTY_PRINT);
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




    private function getRecommend($token, $pageNo, $lang = 'in')
    {
        $client = new \GuzzleHttp\Client([
            'proxy' => \App\Helpers\Dramabox::getProxies(),
            'verify' => false
        ]);

        $payload = [
            'isNeedRank' => 1,
            'specialColumnId' => 0,
            'pageNo' => $pageNo,
        ];

        $deviceId = (string) str()->uuid();
        $headers = \App\Helpers\Dramabox::generateRandomHeaders($lang, $token, $deviceId, \App\Helpers\Dramabox::generateRandomAndroidId(), json_encode($payload));

        $response = $client->request('POST', 'https://sapi.dramaboxdb.com/drama-box/he001/recommendBook?timestamp=' . (now()->timestamp * 1000), [
            'headers' => $headers,
            'body' => json_encode($payload)
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    private function getTheather($token, $lang = 'in')
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

        $headers = \App\Helpers\Dramabox::generateRandomHeaders($lang, $token, $deviceId, \App\Helpers\Dramabox::generateRandomAndroidId(), json_encode($payload));

        $response = $client->request('POST', 'https://sapi.dramaboxdb.com/drama-box/he001/theater?timestamp=' . (now()->timestamp * 1000), [
            'headers' => $headers,
            'body' => json_encode($payload)
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }
    public function fetchPlayers()
    {


        $headers = [
            'Host' => 'sapi.dramaboxdb.com',
            'vn' => '4.8.0',
            'over-flow' => 'new-fly',
            'time-zone' => '+0700',
            'Accept' => '*/*',
            'locale' => 'en_ID',
            'apn' => '2',
            'pline' => 'IOS',
            'mchid' => 'DBDASEO1000000',
            'mcc' => '65535',
            'local-time' => '2025-11-14 00:51:37.350 +0700',
            'User-Agent' => 'DramaBox/4.8.0 (com.storymatrix.drama; build:408001; iOS 26.2.0) Alamofire/5.9.1',
            'version' => '408001',
            'is_vpn' => '0',
            'eighteen-bans' => '0',
            'brand' => 'apple',
            'md' => 'iPhone14,5',
            'mbid' => '41000122793',
            'instanceId' => 'C8F34B882C0E460EA4B271D3EA26054F',
            'Accept-Language' => 'en-ID;q=1.0, id-ID;q=0.9',
            'p' => '49',
            'mf' => 'Apple',
            'package-name' => 'com.storymatrix.drama',
            'current-language' => 'en',
            'storeCountryCode' => 'IDN',
            'srn' => '1170*2532',
            'cid' => 'DLLPF1090829',
            'idfv' => '6C4C9E02-1ABB-4547-91B7-4778DE559268',
            'idfa' => '5B0E1965-8FD0-4C81-BFC4-7A8CFAA68901',
            'afid' => '1763056218083-6490214',
            'tz' => '-420',
            'lat' => '1',
            'adid' => '',
            'sn' => 'F6DYHqDGlN8Xz9fVth6//xMhXYwJcbYFEJBUhMP7Yq2nOtTRQEnhN12IlW74aA1dL/ypJZ65icdGZ+6QLByo8iEic+Y3tK8vZ/eilVWxkjI9m/xSfZ1bh7RRZcWx01vOxa+ApKDM3Q7n4WygySQQvGPZqkYXbJuoVnjvMH3BMNcnwmlgrObYqNGlWjLfQbIvrjfuVbrNpGHJ5CdUdz0Rdu/ESf2wVRUJ7O8xCBbiOJ3dfD1nXZnYjgLLnuOVGM1pKTx+vnoKmbf6hO1VGmbUwmMId082byQ7uLLkQIb38byGRNzJobvcraimFzMgpuaPJ4JDJRm7UwKsDrAYkzdB0w==',
            'Connection' => 'keep-alive',
            'tn' => 'Bearer ZXlKMGVYQWlPaUpLVjFRaUxDSmhiR2NpT2lKSVV6STFOaUo5LmV5SnlaV2RwYzNSbGNsUjVjR1VpT2lKVVJVMVFJaXdpZFhObGNrbGtJam96TXpRd01UQTRNVE45LlZyd214WjdtUUxpNHVsRm8yeEMyS2t3aFNTOUJTblRHMTFvYXR0N3YzbXc=',
            'is_root' => '0',
            'build' => 'iPhone14,5',
            'is_emulator' => '0',
            'language' => 'en',
            'ins' => '1763056233048',
            'device-id' => '9188260bdbe94da3870eb02108e22e5f',
            'active-time' => '64314',
            'ov' => '26.2',
            'Content-Type' => 'application/json',
        ];

        $payload = [
            "bookId" => "41000123213",
            "currencyPlaySource" => "discover_155_rec",
            "currencyPlaySourceName" => "首页发现_Popular_推荐列表",
            "startUpKey" => "4C7B6BF6-5704-4402-99ED-50B632B7812B",
            "preLoad" => false,
            "needEndRecommend" => 0
        ];

        $client = new Client();

        $response = $client->post(
            'https://sapi.dramaboxdb.com/drama-box/chapterv2/batch/load?timestamps=1763056297345',
            [
                'headers' => $headers,
                'json' => $payload
            ]
        );

        $resp = $response->getBody()->getContents();
        return response()->json($resp, 200, [], JSON_PRETTY_PRINT);
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
