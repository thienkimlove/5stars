<?php namespace App\Http\Controllers;

use App\Capture;
use App\Category;
use App\Game;
use App\Http\Requests;
use GuzzleHttp\Client;
use Intervention\Image\Facades\Image;
use PhpSpec\Exception\Exception;
use Symfony\Component\DomCrawler\Crawler;

class CrawlerController extends Controller
{
    public $client;
    public $image;

    function __construct(Client $client)
    {
        $this->client = $client;
        // configure with favored image driver (gd by default)
        Image::configure(['driver' => 'imagick']);
    }

    /**
     * parse
     */
    public function index()
    {

        set_time_limit(0);
        //$this->categoryCrawl('game');

      /*  $request = $this->client->createRequest('GET', 'http://downloader-apk.com/apps/2015/03/04/Facebook 28.0.0.20.16_[www.Downloader-Apk.com].apk');
        $response = $this->client->send($request);
        if ($response->getStatusCode() == '200') {
            dd($response->getBody()->getContents());
        }*/
    }

    public function categoryCrawl($type)
    {
        $case = ($type == 'app') ? 'apps' : 'games';
        $response = $this->crawlerLink('http://www.9apps.com/android-'.$case.'-categories/');
        if ($response) {
            $crawler = new Crawler($response);
            // apply css selector filter
            $filter = $crawler->filter('ul.cate-list > li.item');
            if (iterator_count($filter) > 0) {
                foreach ($filter as $item) {
                    if ($item) {
                        $category = ['type' => $type];
                        $block = new Crawler($item);
                        $category['icon'] = $block->filter('a.inner > img.icon-cate')->attr('dataimg');
                        $category['name'] = $block->filter('div.descr > p.name')->text();
                        $category['icon'] = $this->saveImageFromLink($category['icon'], 'categories');
                        $cat = Category::where('name', $category['name'])->first();
                        //dd($category);
                        if (!$cat) {
                            Category::create($category);
                        } else {
                            $cat->update($category);
                        }
                    }
                }
            }
        }
    }

    /**
     * save one game.
     * @param $item
     */
    protected function saveGames($item)
    {
        $item['type'] = 'game';
        $category = Category::where('name', $item['category'])->first();
        if (!$category) {
            $category = Category::create(['name' => $item['category']]);
        }
        $item['category_id'] = $category->id;
        $item['icon'] = $this->saveImageFromLink($item['icon'], 'avatars');
        $game = Game::create($item);
        foreach ($item['screenshot'] as $urlCapture) {
            $urlCapture = $this->saveImageFromLink($urlCapture, 'captures');
            Capture::create(['name' => $urlCapture, 'game_id' => $game->id]);
        }
    }

    /**
     * save image to public.
     * @param $url
     * @param $case
     * @return string
     */
    protected function saveImageFromLink($url, $case)
    {
        $ars = explode('.', $url);
        $name = md5(time()) . '.' . end($ars);
        $path = public_path() . '/images/' . $case . '/' . $name;
        Image::make($url)->save($path);
        return $name;
    }

    /**
     * get list of apps link
     * @param $filter
     * @return array
     */
    protected function blockItems($filter)
    {
        $result = array();
        if (iterator_count($filter) > 1) {
            // iterate over filter results
            foreach ($filter as $i => $content) {
                // create crawler instance for result
                if ($content) {
                    $crawler = new Crawler($content);
                    // extract the values needed
                    $result[$i] = array(
                        'title' => $crawler->filter('p.name')->text(),
                        'link' => $crawler->filter('a.inner')->attr('href')
                    );
                }

            }
        }
        return $result;
    }

    /**
     * crawl a link     *
     * @param $link
     * @return string
     */
    protected function crawlerLink($link)
    {
        try {
            $request = $this->client->createRequest('GET', $link);
            $response = $this->client->send($request);
            if ($response->getStatusCode() == '200') {
                return $response->getBody()->getContents();
            }
        } catch (Exception $e) {
            dd('error');
        }
    }

    /**
     * crawl a page list apps
     * @param $page
     * @return array
     */
    protected function pageCrawler($page)
    {
        $stores = [];
        $response = $this->crawlerLink('http://www.9apps.com/android-games-' . $page . '/');
        if ($response) {
            $crawler = new Crawler($response);
            // apply css selector filter
            $filter = $crawler->filter('div.list-wrap > ul.list > li.item');
            $result = $this->blockItems($filter);


            foreach ($result as $i => $item) {
                $stores[$i]['site'] = 'http://www.9apps.com';
                $stores[$i]['link'] = $stores[$i]['site'] . $item['link'];
                $response = $this->crawlerLink($stores[$i]['link']);
                if ($response) {
                    $crawler = new Crawler($response);
                    $stores[$i]['icon'] = $crawler->filter('div.main-info > div.pic > img')->attr('dataimg');
                    $stores[$i]['title'] = $crawler->filter('div.text > p.name')->text();
                    if (strpos($stores[$i]['title'], '9Apps') === false) {
                        $stores[$i]['category'] = $crawler->filter('div.text > p.other > a.type')->text();
                        $stores[$i]['total'] = $crawler->filter('div.text > p.other > span')->text();
                        $stores[$i]['total'] = trim(str_replace('| ', '', $stores[$i]['total']));

                        $shotImages = $crawler->filter('div.screenshot > ul > li');

                        $stores[$i]['screenshot'] = [];
                        if (iterator_count($shotImages) > 0) {
                            foreach ($shotImages as $j => $shot) {
                                if ($shot) {
                                    $block = new Crawler($shot);
                                    $stores[$i]['screenshot'][$j] = $block->filter('img')->attr('dataimg');
                                }
                            }
                        }
                        $tempDesc = $crawler->filter('div.panel-descr');
                        $count = 0;
                        if (iterator_count($tempDesc) > 0) {
                            foreach ($tempDesc as $desc) {
                                if ($desc) {
                                    $descBlock = new Crawler($desc);
                                    if ($count == 0) {
                                        $stores[$i]['desc'] = $descBlock->filter('div.panel-bd > p.text')->html();
                                    } else {
                                        $stores[$i]['news'] = $descBlock->filter('div.panel-bd > p.text')->html();
                                    }
                                }
                                $count++;
                            }
                        }

                        $tempInformation = $crawler->filter('div.panel-info > div.panel-bd > p');
                        $count = 0;
                        if (iterator_count($tempInformation) > 0) {
                            foreach ($tempInformation as $info) {
                                if ($info) {
                                    $infoBlock = new Crawler($info);
                                    if ($count == 0) {
                                        $stores[$i]['update'] = trim(str_replace('Update: ', '', $infoBlock->text()));
                                    } else if ($count == 1) {
                                        $stores[$i]['version'] = trim(str_replace('Version: ', '', $infoBlock->text()));
                                    } else {
                                        $stores[$i]['require'] = trim(str_replace('Requires: ', '', $infoBlock->text()));
                                    }
                                }
                                $count++;
                            }
                        }
                        $stores[$i]['download'] = $stores[$i]['site'] . $crawler->filter('div.panel-ft > a.btn-download')->attr('href');
                        //dd($stores[$i]);
                        $this->saveGames($stores[$i]);
                    }

                }

            }
        }

        return $stores;
    }

}
