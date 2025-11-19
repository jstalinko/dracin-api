<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DramaboxService;
use App\Services\NetshortService;
use App\Services\DramawaveService;

class MovieController extends Controller
{
    private $service;
    public function __construct(Request $request)
    {

        $serviceName = $request->route('service');

        $this->service = match ($serviceName) {
            'dramabox' => app(DramaboxService::class),
            'netshort' => app(NetshortService::class),
            'dramawave' => app(DramawaveService::class),
            default => app(DramaboxService::class), // fallback
        };
    }


    public function getTheaters()
    {
        return response()->json($this->service->getTheaters(), 200, [], JSON_PRETTY_PRINT);
    }
    public function getDetail(Request $request)
    {
        $bookId = $request->bookId;
        if (!$bookId) return response()->json(['success' => false, 'message' => 'BookId Required'], 201, [], JSON_PRETTY_PRINT);

        return response()->json($this->service->getDetail($bookId), 200, [], JSON_PRETTY_PRINT);
    }
    public function getChapters(Request $request)
    {
        $bookId = $request->bookId;

        if (!$bookId) return response()->json(['success' => false, 'message' => 'BookId Required'], 201, [], JSON_PRETTY_PRINT);
        return response()->json($this->service->getChapters($bookId), 200, [], JSON_PRETTY_PRINT);
    }
    public function getCategory(Request $request)
    {
        $pageNo = $request->page ?? 1;
        return response()->json($this->service->getCategory($pageNo), 200, [], JSON_PRETTY_PRINT);
    }
    public function getSearch(Request $request)
    {
        $query = $request->input('query') ?? 'cinta';
        if(!$query)
        {
            return response()->json(['success' => false,'message' => 'Error, query required'],201,[],JSON_PRETTY_PRINT);
        }

        return response()->json($this->service->getSearch($query),200,[],JSON_PRETTY_PRINT);
    }
    public function getStream(Request $request)
    {
        $bookId = $request->bookId;
        $episode = $request->episode ?? 1;
        if (!$bookId) return response()->json(['success' => false, 'message' => 'BookId Required'], 201, [], JSON_PRETTY_PRINT);

        return response()->json($this->service->getStream($bookId, $episode), 200, [], JSON_PRETTY_PRINT);
    }
    public function getRecommend()
    {

        return response()->json($this->service->getRecommend(), 200, [], JSON_PRETTY_PRINT);
    }
}
