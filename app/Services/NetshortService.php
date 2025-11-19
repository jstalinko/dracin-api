<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Traits\MovieServiceTrait;
use App\Services\Core\NetshortCore;



class NetshortService
{
    use MovieServiceTrait;

    protected $core;
    public function __construct(Request $request)
    {

        $this->core = new NetshortCore();
        if ($request->lang) {
            $this->core->lang = $request->lang;
        }
    }

    public function getTheaters()
    {
        $data = $this->core->fetchTheaters();
        if (isset($data['data'])) {
            $mapping = [
                'coming_soon' => 'coming_soon',
                'rankings'    => 'you_might_like',
            ];
            $collect = collect($data['data']);
            $datax = $collect->mapWithKeys(function ($item) use ($mapping) {
                $remark = $item['contentRemark'] ?? 'unknown';

                $key = $mapping[$remark] ?? Str::snake($remark);
                $normalized = collect($item['contentInfos'] ?? [])
                    ->map(fn($info) => $this->normalizeContent($info, 'netshort'))
                    ->toArray();

                return [$key => $normalized];
            })->toArray();
            $r['success'] = true;
            $r['message'] = 'Success';
            $r['data'] = $datax;
        } else {
            $r['success'] = false;
            $r['message'] = 'failed';
        }

        return $r;
    }
    public function getCategory($pageNo)
    {
        throw new \Exception('Not implemented');
    }
    public function getDetail($bookId)
    {
        $data =  $this->core->fetchDetail($bookId);
        if (isset($data['data'])) {
            $r['success'] = true;
            $r['message'] = 'Success';
            $r['data'] = $this->normalizeContent($data['data'], 'netshort');
        } else {
            $r['success'] = false;
            $r['message'] = 'Failed';
        }
        return $r;
    }
    public function getChapters($bookId)
    {
        $data = $this->core->fetchDetail($bookId);
        if (isset($data['data'])) {
            $episodes = collect($data['data']['shortPlayEpisodeInfos'])->map(function ($item) {
                $clarity = null;
                if (!empty($item['playClarity'])) {
                    $clarity = (int) filter_var($item['playClarity'], FILTER_SANITIZE_NUMBER_INT);
                }
                return [
                    'id'        => $item['episodeId'],
                    'episode'   => $item['episodeNo'],
                    'cover'     => $item['episodeCover'],
                    'playUrl'   => $item['playVoucher'] ?? null,
                    'clarity'   => $clarity,             // 1080, 720, etc
                    'sdkVid'    => $item['sdkVid'] ?? null,

                    // flags
                    'isLocked'  => (bool) ($item['isLock'] ?? false),
                    'isVip'     => (bool) ($item['isVip'] ?? false),
                    // stats
                    'likes'     => $item['likeNums'] ?? null,
                    'chase'     => $item['chaseNums'] ?? null,
                ];
            })->values();
            $fetchRecommend = $this->core->fetchRecommend($bookId);
            $recommends = collect($fetchRecommend['data']['allShortPlays'])->map(function($item){
                return $this->normalizeContent($item,'netshort');
            })->toArray();
            $datax['episodes'] = $episodes;
            $datax['recommends'] = $recommends;
            $r['success'] = true;
            $r['message'] = 'Success';
            $r['data'] = $datax;
        }else{
            $r['success'] = false;
            $r['message'] = 'Failed';
        }
        return $r;
    }
    public function getSearch($query)
    {
        return $this->core->fetchSearch();

    }

    public function getRecommend()
    {
        $data=  $this->core->fetchRecommendAll();
        if(isset($data['data']))
        {
            $datax = collect($data['data']['contentInfos'])->map(fn($item) => $this->normalizeContent($item,'netshort'));
            $r['success'] = true;
            $r['message'] = 'Succes';
            $r['data'] = $datax;
        }else{
            $r['success'] = false;
            $r['message'] = 'Failed';
        }
        return $r;
    }

    public function getStream($bookId, $eps = 1)
    {
        throw new \Exception('Not implemented');
    }
}
