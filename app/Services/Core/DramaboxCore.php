<?php

namespace App\Services\Core;

use App\Helpers\Dramabox;
use App\Traits\MovieServiceTrait;
use Illuminate\Support\Str;

class DramaboxCore
{
    use MovieServiceTrait;
    private $token;
    private $deviceId;
    private $androidId;
    public $lang;
    public $pageNo;
    public $searchQuery;

    public function __construct()
    {

        $this->deviceId = (string) Str::uuid();
        $this->androidId = Dramabox::generateRandomAndroidId();
        $bootstrap = $this->bootstrap();
        $this->token = $bootstrap['data']['user']['token'];
        $this->lang = 'in';
        $this->pageNo = 1;
        $this->searchQuery = '';
        // dd($bootstrap);

    }

    public function bootstrap()
    {
        $length = 7;
        $randomBytes = random_bytes($length);
        $distinctId = bin2hex($randomBytes);
        $instanceId = Str::upper(Str::random(32));
        $payload = [
            'distinctId' => $distinctId
        ];
        $headers = [
            'host' => 'sapi.dramaboxdb.com',
            'over-flow' => 'new-fly',
            'vn' => '4.7.0',
            'time-zone' => '+0700',
            'accept' => '*/*',
            'local-time' => date('Y-m-d') . ' ' . date('H:m:i') . '.' . rand(100, 900) . ' +0700',
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
            'instanceid' => $instanceId,
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
            'language' => 'en',
            'device-id' => '38302440b1684bac86e321eefbb710f4',
            'ov' => '18.6.2',
            'active-time' => '178',
            'is_emulator' => '0',
            'content-type' => 'application/json'
        ];

        try {
            $response = $this->http('POST', 'https://sapi.dramaboxdb.com/drama-box/ap001/bootstrap?timestamps=1762833217803', [
                'headers' => $headers,
                'body' => '{}'
            ]);

            return $response;
        } catch (\Exception $e) {
            return [
                'sucess' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function fetchTheater()
    {

        $payload = [
            'isNeedRank' => 1,
            'isNeedNewChannel' => 1,
            'index' => 0,
            'type' => 0,
            'channelId' => 155,
        ];
        $deviceId = (string) str()->uuid();
        $androidId = Dramabox::generateRandomAndroidId();
        $headers = Dramabox::generateRandomHeaders($this->lang, $this->token, $deviceId, $androidId, json_encode($payload));
        $timestamp = now()->timestamp * 1000;

        $response = $this->http(
            'POST',
            'https://sapi.dramaboxdb.com/drama-box/he001/theater?timestamp=' . $timestamp,
            [
                'headers' => $headers,
                'body'    => json_encode($payload)
            ]
        );

        return $response;
    }

    public function fetchCategory($pageNo)
    {
        $typeList = [];
        $pageSize = 15;
        $timestamp = now()->timestamp * 1000;
        $payload = [
            'typeList' => $typeList,
            'showLabels' => true,
            'pageNo' => $pageNo ?? $this->pageNo,
            'pageSize' => $pageSize,
        ];
        $headers = Dramabox::generateRandomHeaders($this->lang, $this->token, $this->deviceId, $this->androidId, json_encode($payload));
        $response = $this->http(
            'POST',
            'https://sapi.dramaboxdb.com/drama-box/he001/classify?timestamp=' . $timestamp,
            [
                'headers' => $headers,
                'body' => json_encode($payload)
            ]
        );

        return $response;
    }

    public function fetchTheaterDetail($bookId)
    {
        $payload = [
            'bookId' => "$bookId"
        ];
        $timestamp = now()->timestamp * 1000;
        $headers = Dramabox::generateRandomHeaders($this->lang, $this->token, $this->deviceId, $this->androidId, json_encode($payload));
        $response = $this->http(
            'POST',
            'https://sapi.dramaboxdb.com/drama-box/he001/reserveBookDetail?timestamp=' . $timestamp,
            [
                'headers' => $headers,
                'body' => json_encode($payload)
            ]
        );
        return $response;
    }
    public function fetchSearch($query,$pageNo = 1) {
         $pageSize = 20;
        $searchSource = '搜索按钮';
        $from = 'search_sug';

        if (empty($query)) {
            return response()->json(['error' => 'kata kunci pencarian wajib diisi'], 400);
        }

        $timestamp = now()->timestamp * 1000;

        $payload = [
            'searchSource' => $searchSource,
            'pageNo' => $pageNo ?? $this->pageNo,
            'pageSize' => $pageSize,
            'from' => $from,
            'keyword' => $query,
        ];
        
        $headers = Dramabox::generateRandomHeaders($this->lang, $this->token, $this->deviceId, $this->androidId, json_encode($payload));

        $response = $this->http('POST','https://sapi.dramaboxdb.com/drama-box/search/search?timestamp='.$timestamp , [
            'headers' => $headers,
            'body' => json_encode($payload)
        ]);

        return $response;
    }

    public function fetchRecommend()
    {

        $payload = [
            'isNeedRank' => 1,
            'specialColumnId' => 0,
            'pageNo' => $this->pageNo,
        ];
        $timestamp = now()->timestamp * 1000;
        $headers = Dramabox::generateRandomHeaders($this->lang, $this->token, $this->deviceId, $this->androidId, json_encode($payload));
        $response = $this->http('POST', 'https://sapi.dramaboxdb.com/drama-box/he001/recommendBook?timestamp=' . $timestamp, [
            'headers' => $headers,
            'body' => json_encode($payload)
        ]);

        return $response;
    }
    public function fetchChapterDetail($bookId)
    {
        $payload = [
            'bookId' => "$bookId"
        ];
        $timestamp = now()->timestamp * 1000;
        $headers = Dramabox::generateRandomHeaders($this->lang, $this->token, $this->deviceId, $this->androidId, json_encode($payload));
        $response = $this->http(
            'POST',
            'https://sapi.dramaboxdb.com/drama-box/chapterv2/detail?timestamp=' . $timestamp,
            [
                'headers' => $headers,
                'body' => json_encode($payload)
            ]
        );
        return $response;
    }

    
    public function fetchStream($bookId,$eps = 1)
    {
         $payload = [
            'boundaryIndex' => ($eps-1) ?? 0,
            'index' => ($eps-1) ?? 0,
            'comingPlaySectionId' => -1,
            'needEndRecommend' => 0,
            'currencyPlaySource' => 'discover_new_rec_new',
            'currencyPlaySourceName' => '',
            'preLoad' => true,
            'rid' => '',
            'pullCid' => '',
            'loadDirection' => 1,
            'startUpKey' => '3ec5ff61-a537-4e13-8934-aa151e05ea0d',
            'bookId' => $bookId,
        ];
        $timestamp = now()->timestamp * 1000;
        $headers = Dramabox::generateRandomHeaders($this->lang, $this->token, $this->deviceId, $this->androidId, json_encode($payload));
        $response = $this->http(
            'POST',
            'https://sapi.dramaboxdb.com/drama-box/chapterv2/batch/load?timestamp=' . $timestamp,
            [
                'headers' => $headers,
                'body' => json_encode($payload)
            ]
        );
        return $response;
    }

}
