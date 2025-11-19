<?php

namespace App\Services;

use App\Helpers\Dramabox;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Traits\MovieServiceTrait;
use App\Services\Core\DramaboxCore;

class DramaboxService
{
    use MovieServiceTrait;
    protected $core;
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

    public function getTheaters()
    {
        $data = $this->core->fetchTheater();
        if (isset($data['data'])) {
            $collect = collect($data['data']['columnVoList']);

            $datax = $collect->mapWithKeys(function ($item) {
                $key = Str::snake(strtolower($item['title'] ?? 'unknown'));

                // bookList adalah array -> kita harus map satu-satu
                $normalized = collect($item['bookList'] ?? [])
                    ->map(fn($book) => $this->normalizeContent($book))
                    ->toArray();

                return [$key => $normalized];
            })->toArray();


            $r['success'] = true;
            $r['message'] = 'Success';
            $r['data'] = $datax;
        } else {
            $r['success'] = false;
            $r['message'] = 'Failed';
        }
        return $r;
    }
    public function getCategory($pageNo)
    {
        $data = $this->core->fetchCategory($pageNo);
        if (isset($data['data'])) {
            $r['success'] = true;
            $r['message'] = 'Success';
            $r['data'] = $data;
        } else {
            $r['success'] = false;
            $r['message'] = 'Failed';
        }
        return $r;
    }
    public function getDetail($bookId)
    {
        $data = $this->core->fetchTheaterDetail($bookId);
        if (isset($data['data'])) {
            $r['success'] = true;
            $r['message'] = 'Succes';
            $r['data'] = $this->normalizeContent($data['data'], 'dramabox');
        } else {
            $r['success'] = false;
            $r['message'] = 'Failed';
        }
        return $r;
    }
    public function getChapters($bookId)
    {
        $data = $this->core->fetchChapterDetail($bookId);
        if (isset($data['data'])) {

            $episodes = collect($data['data']['list'])->map(function ($item) {
                $qualities = collect($item['chapterSizeVoList'] ?? [])
                    ->pluck('quality')                            // [720, 540, 360, ...]
                    ->filter(fn($q) => $q !== null && $q !== '') // drop null/empty
                    ->map(fn($q) => (int) $q)                    // ensure int
                    ->unique()                                   // unique qualities
                    ->sort()                                     // ascending
                    ->reverse()                                  // descending (highest first)
                    ->values()
                    ->toArray();
                return [
                    'id' => $item['chapterId'],
                    'episode' => $item['chapterIndex'],
                    'quality' => $qualities,
                    'isLocked' => (bool) $item['isPay'],
                ];
            })->values();

            $recommends = collect($data['data']['recommendList'] ?? [])
                ->map(fn($book) => $this->normalizeContent($book))
                ->toArray();



            $datax['episodes'] = $episodes;
            $datax['recommendList'] = $recommends;
            $r['success'] = true;
            $r['message'] = 'Success';
            $r['data'] = $datax;
        } else {
            $r['success'] = false;
            $r['message'] = 'Failed';
        }
        return $r;
    }
    public function getSearch($query)
    {
        $data = $this->core->fetchSearch($query, $this->core->pageNo);
        if (isset($data['data'])) {
            $datax = collect($data['data']['searchList'] ?? [])->map(fn($item) => $this->normalizeContent($item))->toArray();
            $r['success'] = true;
            $r['message'] = 'Success';
            $r['data'] = $datax;
        } else {
            $r['success'] = false;
            $r['message'] = 'Failed';
        }
        return $r;
    }

    public function getStream($bookId, $eps = 1)
    {
        $data = $this->core->fetchStream($bookId, $eps);
        if (isset($data['data'])) {
            $chapters = collect($data['data']['chapterList'] ?? [])
                ->map(fn($item) => Dramabox::normalizeFromDramabox($item))
                ->toArray();
            $r['success'] = true;
            $r['message'] = 'Success';
            $r['data'] = $chapters;
        } else {
            $r['success'] = false;
            $r['message'] = 'Failed';
        }

        return $r;
    }

    public function getRecommend()
    {
        $data = $this->core->fetchRecommend();
        if (isset($data['data'])) {
            $recommends = collect($data['data']['recommendList']['records'] ?? [])
                ->map(fn($item) => $this->normalizeContent($item))
                ->toArray();

            $r['success'] = true;
            $r['message'] = 'Success';
            $r['data'] = $recommends;
        } else {
            $r['success'] = false;
            $r['message'] = 'Failed';
        }

        return $r;
    }
}
