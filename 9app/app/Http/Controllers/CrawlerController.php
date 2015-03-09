<?php namespace App\Http\Controllers;

use App\Crawlers\GoogleCrawler;
use App\Crawlers\MainCrawler;
use App\Crawlers\ReaderCrawler;

class CrawlerController extends Controller
{

    /**
     * parse
     */
    public function index()
    {
        $crawler = new ReaderCrawler();
        $crawler->createPackages();
    }

    /**
     *
     */
    public function import()
    {
        $crawler = new MainCrawler();
        $crawler->import(5000);

    }
}
