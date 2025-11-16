<?php 
namespace App\Interfaces;


interface MovieServiceInterface {

    public function fetchTheater();

    public function fetchChapters();

    public function fetchPlayers();

    public function fetchTheaterDetails($theaterId);

    public function fetchRecommend($pageNo);

    public function fetchTheaterRecommendationDetail($theaterId);

    public function fetchCategory();
}