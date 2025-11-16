<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DramaboxService;
use App\Services\NetshortService;
use App\Interfaces\MovieServiceInterface;

class MovieController extends Controller
{
    protected MovieServiceInterface $movieService;
    public function __construct(Request $request)
    {
        $serviceName = $request->route('service');

        $this->movieService = match ($serviceName) {
            'dramabox' => app(DramaboxService::class),
            'netshort' => app(NetshortService::class),
            default => app(DramaboxService::class), // fallback
        };
    }

    public function getTheaters()
    {
        return $this->movieService->fetchTheater();
    }
    public function getPlayers()
    {
        return $this->movieService->fetchPlayers();
    }

    public function getRecommend(Request $request)
    {
        $pageNo = $request->pageNo ?? 1;
        
        return $this->movieService->fetchRecommend($pageNo);
    }
}
