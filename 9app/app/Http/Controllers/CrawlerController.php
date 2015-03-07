<?php namespace App\Http\Controllers;

use App\Capture;
use App\Category;
use App\Game;
use App\Http\Requests;
use App\Package;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Exception\NotReadableException;
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
     * save image to public.
     * @param $url
     * @param $case
     * @return string
     */
    protected function saveImageFromLink($url, $case)
    {
        $ars = explode('.', $url);
        $ext = end($ars);
        if (in_array($ext, ['jpg', 'png', 'jpeg', 'gif'])) {
            $name = md5(time()) . '.' . end($ars);
        } else {
            $name = md5(time()) . '.png';
        }
        $path = public_path() . '/images/' . $case . '/' . $name;
        try {
            Image::make($url)->save($path);
        } catch (NotReadableException $e) {
            return;
        }
        return $name;
    }


    /**
     * crawl a link     *
     * @param $link
     * @param bool $redirect
     * @return string
     */
    protected function crawlerLink($link, $redirect = false)
    {
        try {
            $request = $this->client->createRequest('GET', $link, ['allow_redirects' => $redirect]);
            $response = $this->client->send($request);
            if ($response->getStatusCode() == '200') {
                return $response->getBody()->getContents();
            }
        } catch (Exception $e) {
            dd('error');
        }
    }

    /**
     * get link download from download-apk.com
     * @param $package
     * @return string|void
     */
    protected function getLinkDownloadApk($package)
    {
        $response = $this->crawlerLink('http://downloader-apk.com/download/dl.php?dl=' . $package, false);
        $response = preg_split('/\r\n|\n|\r/', $response);
        foreach ($response as $line) {
            if (strpos($line, "setTimeout('location.href=") !== false) {
                $line = str_replace('setTimeout(\'location.href="..', 'http://downloader-apk.com', $line);
                $line = str_replace('"\',25000);', '', $line);
                return trim($line);
            }
        }
        return;
    }


    /**
     * parse
     */
    public function index()
    {
        set_time_limit(0);
        //get the list of package.
        /* if ($page == 'google_new_free') {
             $url = 'https://play.google.com/store/apps/collection/topselling_new_free?hl=en&gl=us';
             $packages = $this->googlePackageListFromPage($url);
         } else if ($page == 'google_free') {
             $url = 'https://play.google.com/store/apps/collection/topselling_free?hl=en&gl=us';
             $packages = $this->googlePackageListFromPage($url);
         } else if ($page == 'google_cate') {
             $url = 'https://play.google.com/store/apps/category/GAME_ACTION/collection/topselling_free?hl=en&gl=us';
             $packages = $this->googlePackageListFromPage($url);
         }*/

        DB::table('stores')->truncate();
        $response = $this->crawlerLink('https://play.google.com/store/apps?hl=en&gl=us');
        $crawler = new Crawler($response);
        $links = $crawler->filter('body a.child-submenu-link');
        $data = [];
        foreach ($links as $i => $link) {
            $temp = new Crawler($link);
            $data[$i] = 'https://play.google.com' . $temp->attr('href') . '/collection/topselling_free?hl=en&gl=us';
        }
        foreach ($data as $item) {
            $packages = $this->googlePackageListFromPage($item);
            foreach ($packages as $package) {
                DB::table('stores')->insert([
                    'name' => $package,
                    'status' => 'not'
                ]);
            }
        }
    }

    /**
     * get list of package name in table store and then process.
     *
     */
    public function import()
    {
        set_time_limit(0);
        $lists = DB::table('stores')->where('status', 'not')->lists('name');
        foreach ($lists as $list) {
            $this->addToPackages($list);
            DB::table('stores')
                ->where('name', $list)
                ->update(['status' => 'done']);
        }
    }


    /**
     * Google Store
     * later we must add many of this function.
     * get the array of packages name list from a page.
     * @param $url
     * @return array
     */
    protected function googlePackageListFromPage($url)
    {
        $topNewFree = $this->crawlerLink($url);
        $crawler = new  Crawler($topNewFree);
        $items = $crawler->filter('div.card-list > div.card');
        $packages = [];
        foreach ($items as $item) {
            $game = new Crawler($item);
            $temp = $game->filter('div.card-content > div.cover > a.card-click-target')->attr('href');
            $packages[] = str_replace('/store/apps/details?id=', '', $temp);
        }
        return $packages;
    }

    /** check if package name exist on table packages
     * if not, then add item to game and add to packages.
     * @param $game_id
     * @param $temp
     */
    protected function addToPackages($temp)
    {
        $check = Package::where('name', $temp)->first();
        if (!$check) {
            //add to game.
            $this->addToGame($temp);
        }
    }

    /**
     * crawl details page by package name on google store.
     * @param $package
     * @return CrawlerController|void
     */
    protected function addToGame($package)
    {
        $link = 'https://play.google.com/store/apps/details?id=' . $package . '&hl=en&gl=us';
        $response = $this->crawlerLink($link);
        $crawler = new  Crawler($response);

        $data = [];
        $data['icon'] = $crawler->filter('div.details-info > div.cover-container > img.cover-image')->attr('src');
        $data['title'] = $crawler->filter('div.details-info > div.info-container > div.document-title > div')->text();
        $type = $crawler->filter('div.details-info > div.info-container a.category')->attr('href');

        $data['type'] = (strpos($type, 'GAME_') !== false) ? 'games' : 'apps';
        $data['category'] = $crawler->filter('div.details-info > div.info-container a.category > span')->text();
        $data['screens'] = [];
        $captures = $crawler->filter('div.screenshots img.screenshot');
        if (iterator_count($captures) > 0) {
            foreach ($captures as $capture) {
                $temp = new Crawler($capture);
                $data['screens'][] = $temp->attr('src');
            }
        }

        $tempMeta = $crawler->filter('div.metadata div.details-section-contents > div.meta-info');

        if (iterator_count($tempMeta) > 0) {
            foreach ($tempMeta as $i => $meta) {
                $tempUpdate = new Crawler($meta);
                if ($i == 0) {
                    $data['update'] = $tempUpdate->filter('div.content')->text();
                    $data['update'] = Carbon::parse($data['update'])->format('Y-m-d');
                }
                if ($i == 1) {
                    $data['total'] = $tempUpdate->filter('div.content')->text();
                    $data['total'] = trim($data['total']);
                }
                if ($i == 3) {
                    $data['version'] = $tempUpdate->filter('div.content')->text();
                    $data['version'] = trim($data['version']);
                }
                if ($i == 4) {
                    $data['require'] = $tempUpdate->filter('div.content')->text();
                    $data['require'] = trim($data['require']);
                }

            }
        }

        $data['desc'] = $crawler->filter('div.details-section-contents div.id-app-orig-desc')->html();
        $data['news'] = $crawler->filter('div.whatsnew div.recent-change');
        if (count($data['news'])) {
            $data['news'] = $data['news']->html();
        } else {
            $data['news'] = '';
        }
        $data['link'] = $link;
        $data['site'] = 'https://play.google.com';
        $data['download'] = @$this->getLinkDownloadApk($package);
        if (!$data['download']) {
            $data['download'] = $link;
        }
        $this->saveGames($data, $package);
    }

    /**
     * save one game.
     * @param $data
     * @param $package
     * @return static
     * @internal param $item
     */
    protected function saveGames($data, $package)
    {
        $data['icon'] = $this->saveImageFromLink($data['icon'], 'avatars');
        if ($data['icon']) {
            $category = Category::where('name', $data['category'])->first();
            if (!$category) {
                copy(public_path() . '/images/avatars/' . $data['icon'], public_path() . '/images/categories/' . $data['icon']);
                $category = Category::create(['name' => $data['category'], 'icon' => $data['icon'], 'type' => $data['type']]);
            }
            $data['category_id'] = $category->id;
            $game = Game::create($data);
            foreach ($data['screens'] as $urlCapture) {
                $urlCapture = $this->saveImageFromLink($urlCapture, 'captures');
                if ($urlCapture) {
                    Capture::create(['name' => $urlCapture, 'game_id' => $game->id]);
                }
            }
            try {
                Package::create([
                    'game_id' => $game->id,
                    'name' => $package
                ]);
            } catch (QueryException $e) {
                DB::table('packages')->where('name', $package)->delete();
                Package::create([
                    'game_id' => $game->id,
                    'name' => $package
                ]);
            }
        }
    }
}
