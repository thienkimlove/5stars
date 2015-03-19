<?php namespace App\Http\Controllers;

use App\Category;
use App\Event;
use App\Http\Requests;
use App\Store;
use App\Coupon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\TransferException;
use Illuminate\Database\QueryException;
use Intervention\Image\Exception\NotReadableException;

use Intervention\Image\Facades\Image;
use InvalidArgumentException;
use Symfony\Component\DomCrawler\Crawler;


class CrawlerController extends Controller
{

    public $client;

    function __construct()
    {
        $this->client = new Client();
    }

    /**
     * save image from link or from data-url or from binary source.
     * @param $src
     * @param string $case
     * @return Image|void
     * @internal param $path
     */
    protected function downloadImage($src, $case = 'stores')
    {
        try {
            $img = Image::make($src);
            $name = md5(time()) . '.' . str_replace('image/', '', $img->mime());
            $img->save(public_path(). '/images/'. $case . '/' . $name);
            return $name;
        } catch (NotReadableException $e) {
            return '';
        }
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
        } catch (TransferException $e) {
            return;
        }
    }

    /**
     * index
     */
    public function index()
    {
        //Event::create(['name' => 'Test']);

        /*foreach([1, 2, 3] as $page) {
            $this->saveCoupons('http://www.mostcoupon.com/coupons/accessories?_page='.$page);
        }*/

       //$this->saveCategories();

        $this->byCategory(1);



    }

    /**
     * save coupons for each category.
     * @param $id
     */
    protected function byCategory($id)
    {
        $category = Category::find($id);
        if ($category->page == 1) {
            $this->saveCoupons($category->link, $category->id);
        } else {
            for ($i = 1;  $i < $category->page ; $i ++) {
                $this->saveCoupons($category->link.'?_page='.$i, $category->id);
            }
        }
    }

    /**
     * save categories and link and number of page.
     */
    protected function saveCategories()
    {
        $response = $this->crawlerLink('http://www.mostcoupon.com/categories-listing');
        $crawler =  new Crawler($response);
        $menu = $crawler->filter('body div.page_main ul.list-categories > li');
        $data = [];
        foreach ($menu as $i => $item) {
            $tempCrawler = new Crawler($item);
            $data[$i]['name'] = trim($tempCrawler->filter('a.icon')->attr('title'));

            $check = Category::where('name', $data[$i]['name'])->first();
            if (!$check) {
                $data[$i]['link'] = $tempCrawler->filter('a.icon')->attr('href');
                //icon download.
                $data[$i]['icon'] = $tempCrawler->filter('a.icon > img')->attr('src');
                $data[$i]['icon'] = $this->downloadImage($data[$i]['icon'], 'categories');

                //get page number.
                $response = $this->crawlerLink($data[$i]['link']);
                $crawler = new Crawler($response);
                try {
                    $last = $crawler->filter('div.page_main div#pagination > a:last-child')->attr('href');
                    $temp = str_replace($data[$i]['link'].'?_page=', '', $last);
                    $temp = str_replace('&show=coupons', '', $temp);
                    $data[$i]['page'] = (int) trim($temp);
                } catch (InvalidArgumentException $e) {
                    $data[$i]['page'] = 1;
                }

                //create
                Category::create($data[$i]);
            }
        }
    }

    /**
     * save articles for a page which display list.
     * @param $url
     * @param $category_id
     */
    protected function saveCoupons($url, $category_id)
    {
        $response = $this->crawlerLink($url);
        $crawler = new Crawler($response);
        $coupons = $crawler->filter('div#show_coupons > article');
        $data = [];
        foreach ($coupons as $i => $item) {
            $itemCrawler = new Crawler($item);

            $temp = $itemCrawler->filter('div.col-md-10 > div.shop-at > a')->attr('href');
            $temp = str_replace('http://www.mostcoupon.com/', '', $temp);
            $temp = str_replace('-coupons', '', $temp);
            $store = Store::where('slug', trim($temp))->first();
            if (!$store) {
                $name = $itemCrawler->filter('div.col-md-10 > div.shop-at > a')->text();
                $image = $itemCrawler->filter('div.logo > img')->attr('src');
                $image = $this->downloadImage($image);
                $store = Store::create(['name' => $name, 'logo' => $image, 'category_id' => $category_id]);
            }
            $data[$i]['event_id'] = 1;
            $data[$i]['store_id'] = $store->id;
            $data[$i]['title'] = $itemCrawler->filter('div.col-md-10 > p.title')->text();


            try {
                $expired = $itemCrawler->filter('div.col-md-10 > div.expries > span.note')->text();
                $data[$i]['expired_date'] = str_replace('End: ', '', $expired);
            } catch(InvalidArgumentException $e) {}

            try {
                $data[$i]['coupon_code'] = $itemCrawler->filter('div.get-code > div.action-mask  div.action-wrap > p')->text();
                $data[$i]['coupon_type'] = 'code';
            } catch (InvalidArgumentException $e) {
                if ($itemCrawler->filter('div.get-code > div.action-mask > span.action')->text() == 'Free Shipping') {
                    $data[$i]['coupon_type'] = 'ship';
                } else {
                    $data[$i]['coupon_type'] = 'deal';
                }
            }

            $data[$i]['desc'] = $itemCrawler->filter('div.description > p.description')->html();
            $temp = $itemCrawler->filter('div.information > span.expries')->text();
            $data[$i]['published_date'] = str_replace('Publish: ', '', $temp);
            try {
                Coupon::create($data[$i]);
            } catch(QueryException $e) {

            }
        }
    }

}
