<?php 
namespace App\Interfaces;


interface MovieServiceInterface {

    public function fetchTheater();

    public function fetchChapters();

    public function fetchTheaterDetails($theaterId);
}