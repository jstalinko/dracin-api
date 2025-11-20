<?php

namespace App\Services;

use App\Helpers\Dramabox;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Traits\MovieServiceTrait;
use App\Services\Core\DramaboxCore;

class DramaboxService
{
    use MovieServiceTrait;

    protected $core;

    // TTL cache dalam detik
    private int $ttlCache = 60 * 30; // 30 menits

    public function __construct(Request $request)
    {
        $this->core = new DramaboxCore();

        if ($request->lang) {
            $this->core->lang = $request->lang;
        }
        if ($request->page) {
            $this->core->pageNo = (int) $request->page;
        }
    }

    /**
     * UNIVERSAL CACHE WRAPPER
     */
    private function cacheResponse(string $method, array $args, callable $callback)
    {
        $key = 'dramabox:' . $method . ':' . md5(json_encode($args));

        return Cache::remember($key, $this->ttlCache, function () use ($callback,$key) {

            $response = $callback();

            // jika gagal, tidak usah disimpan di cache
            if (!($response['success'] ?? false)) {
                Cache::forget($key);
            }

            return $response;
        });
    }

    public function getTheaters()
    {
        return $this->cacheResponse(__FUNCTION__, [], function () {
            $data = $this->core->fetchTheater();

            if (!isset($data['data'])) {
                return ['success' => false, 'message' => 'Failed'];
            }

            $collect = collect($data['data']['columnVoList']);

            $datax = $collect->mapWithKeys(function ($item) {
                $key = Str::snake(strtolower($item['title'] ?? 'unknown'));
                $normalized = collect($item['bookList'] ?? [])
                    ->map(fn($book) => $this->normalizeContent($book))
                    ->toArray();
                return [$key => $normalized];
            })->toArray();

            return [
                'success' => true,
                'message' => 'Success',
                'data'    => $datax,
            ];
        });
    }

    public function getCategory($pageNo)
    {
        return $this->cacheResponse(__FUNCTION__, [$pageNo], function () use ($pageNo) {

            $data = $this->core->fetchCategory($pageNo);

            return isset($data['data'])
                ? ['success' => true, 'message' => 'Success', 'data' => $data]
                : ['success' => false, 'message' => 'Failed'];
        });
    }

    public function getDetail($bookId)
    {
        return $this->cacheResponse(__FUNCTION__, [$bookId], function () use ($bookId) {

            $data = $this->core->fetchTheaterDetail($bookId);

            return isset($data['data'])
                ? [
                    'success' => true,
                    'message' => 'Succes',
                    'data'    => $this->normalizeContent($data['data'], 'dramabox'),
                ]
                : ['success' => false, 'message' => 'Failed'];
        });
    }

    public function getChapters($bookId)
    {
        return $this->cacheResponse(__FUNCTION__, [$bookId], function () use ($bookId) {
            $data = $this->core->fetchChapterDetail($bookId);

            if (!isset($data['data'])) {
                return ['success' => false, 'message' => 'Failed'];
            }

            $episodes = collect($data['data']['list'])->map(function ($item) {
                $qualities = collect($item['chapterSizeVoList'] ?? [])
                    ->pluck('quality')
                    ->filter(fn($q) => $q !== null && $q !== '')
                    ->map(fn($q) => (int) $q)
                    ->unique()
                    ->sort()
                    ->reverse()
                    ->values()
                    ->toArray();

                return [
                    'id'       => $item['chapterId'],
                    'episode'  => $item['chapterIndex'],
                    'quality'  => $qualities,
                    'isLocked' => (bool) $item['isPay'],
                ];
            })->values();

            $recommends = collect($data['data']['recommendList'] ?? [])
                ->map(fn($book) => $this->normalizeContent($book))
                ->toArray();

            return [
                'success' => true,
                'message' => 'Success',
                'data' => [
                    'episodes'      => $episodes,
                    'recommendList' => $recommends,
                ]
            ];
        });
    }

    public function getSearch($query)
    {
        return $this->cacheResponse(__FUNCTION__, [$query, $this->core->pageNo], function () use ($query) {

            $data = $this->core->fetchSearch($query, $this->core->pageNo);

            return isset($data['data'])
                ? [
                    'success' => true,
                    'message' => 'Success',
                    'data'    => collect($data['data']['searchList'] ?? [])
                        ->map(fn($item) => $this->normalizeContent($item))
                        ->toArray(),
                ]
                : ['success' => false, 'message' => 'Failed'];
        });
    }

    public function getStream($bookId, $eps = 1)
    {
        return $this->cacheResponse(__FUNCTION__, [$bookId, $eps], function () use ($bookId, $eps) {

            $data = $this->core->fetchStream($bookId, $eps);

            return isset($data['data'])
                ? [
                    'success' => true,
                    'message' => 'Success',
                    'data'    => collect($data['data']['chapterList'] ?? [])
                        ->map(fn($item) => Dramabox::normalizeFromDramabox($item))
                        ->toArray(),
                ]
                : ['success' => false, 'message' => 'Failed'];
        });
    }

    public function getRecommend()
    {
        return $this->cacheResponse(__FUNCTION__, [], function () {

            $data = $this->core->fetchRecommend();

            return isset($data['data'])
                ? [
                    'success' => true,
                    'message' => 'Success',
                    'data'    => collect($data['data']['recommendList']['records'] ?? [])
                        ->map(fn($item) => $this->normalizeContent($item))
                        ->toArray(),
                ]
                : ['success' => false, 'message' => 'Failed'];
        });
    }
}
