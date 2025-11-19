<?php

namespace App\Traits;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;


trait MovieServiceTrait
{

    public function getTheaters() {}
    public function getDetail($bookId) {}
    public function getChapters($bookId) {}

    public function getStream($bookId,$eps = 1){}
    public function getCategory($pageNo = 1) {}
    public function getSearch(string $query) {}
    public function getRecommend(){}

    public function response_success(array $data, int $code, string $message = 'ok')
    {
        $r['success'] = true;
        $r['code'] = $code;
        $r['message'] = $message;
        $r['data'] = $data;
        return response()->json($r, $code, [], JSON_PRETTY_PRINT);
    }

    public function response_error(int $code, string $message, $errors)
    {
        $r['success'] = false;
        $r['code'] = $code;
        $r['message'] = $message;
        $r['errors'] = $errors;

        return response()->json($r, $code, [], JSON_PRETTY_PRINT);
    }

    public function getProxies()
    {
        $filePath = app_path('Helpers/Proxies');
        if (file_exists($filePath)) {
            try {
                $proxies = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                $selectProxy = explode(":", $proxies[array_rand($proxies)]);
                $ip = $selectProxy[0];
                $port = $selectProxy[1];
                $username = $selectProxy[2];
                $password = $selectProxy[3];
                return "http://$username:$password@$ip:$port";
            } catch (\Throwable $th) {
                return null;
            }
        } else {
            return null;
        }
    }
   
public function http(string $method, string $url, array $options = [])
{
    $method = strtoupper($method);
    $client = new Client([
        'proxy'   => $this->getProxies(),
        'verify'  => false,
        'verbose' => true
    ]);

    $response = $client->request($method, $url, $options);
    $body = json_decode($response->getBody()->getContents(), true);


    return $body;
}
public function array_get_multi($array, $paths, $default = null)
{
    foreach ($paths as $path) {
        $value = data_get($array, $path);
        if ($value !== null) {
            return $value;
        }
    }
    return $default;
}

public function normalizeContent($item,$service = 'dramabox')
{
    return [
        'id' => $this->array_get_multi($item, [
            'bookId',
            'id',
            'shortPlayId',
            'shortPlayLibraryId',
        ]),

        'title' => $this->array_get_multi($item, [
            'bookName',
            'shortPlayName',
        ]),

        'image' => $this->array_get_multi($item, [
            'coverWap',
            'shortPlayCover',
            'groupShortPlayCover',
            'cover'
        ]),

        'tags' => $this->array_get_multi($item, [
            'tags',
            'labelArray',
            'tagNames'
        ], []),
        'views' => $this->array_get_multi($item,[
            'playCount',
            'totalReserveNum'
        ],0),
        'episode' => $this->array_get_multi($item,[
            'chapterCount'
        ],0),

        'introduction' => $this->array_get_multi($item,[
            'introduction'
        ],'-'),
        'source' => $service,
    ];
}



    public function generateCacheKey($method,$url,$options)
    {
        $rawKey = $method . '|' . $url ;
        return 'http_cache_' . md5($rawKey);
    }
}
