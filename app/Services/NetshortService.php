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
        throw new \Exception('Not implemented');
    }
    public function getChapters($bookId)
    {
        throw new \Exception('Not implemented');
    }
    public function getSearch($query)
    {
        throw new \Exception('Not implemented');
    }

    public function getStream($bookId, $eps = 1)
    {
        throw new \Exception('Not implemented');
    }
}
