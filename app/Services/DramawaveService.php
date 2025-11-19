<?php

namespace App\Services;

use App\Traits\MovieServiceTrait;


class DramawaveService
{
    use MovieServiceTrait;

    public function getTheaters()
    {
        throw new \Exception('Not implemented');
    }
    public function getCategory()
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
