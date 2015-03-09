<?php namespace App\Http\Controllers;

use App\Category;
use App\Crawlers\MainCrawler;
use App\Game;
use App\Http\Requests;
use Illuminate\Http\Request;

class MainController extends Controller
{

    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $css = 'home';
        $pageHome = true;
        $games = Game::latest('update')->take(20)->get();

        return view('games.home', compact('games', 'pageHome', 'css'))->with([
            'title' => 'Free Android Apps Download | Best Games for Android Mobile Phone - AppForAndroidPhone',
            'desc' => 'AppForAndroidPhone.com supports free android apps and games apk download. Thousands of top best android apps at AppForAndroidPhone! Play free apps and games for android mobile phone now!',
            'keyword' => 'android apps,android apps download,free android apps,best android apps,apps for android,appforandroiphone,games, android games download, android games, free android games'
        ]);
    }


    /**
     * @param $type
     * @return \Illuminate\View\View
     * @internal param $page
     * @internal param Request $request
     */
    public function games()
    {
        $css = 'app';
        $games = Game::where('type', 'games')->latest('update')->paginate(20);
        $categories = Category::where('type', 'games')->latest()->take(7)->get();
        $pageGame = true;

        return view('games.app', compact('games', 'pageGame', 'categories', 'css'))->with([
            'title' => 'Hot Android Games Free download - AppForAndroidPhone',
            'desc' => 'Looking for some hot games to play on your Android device? AppForAndroidPhone.com supports top best new Android games download.',
            'keyword' => 'hot android games, free android games'
        ]);
    }


    /**
     * @param $type
     * @return \Illuminate\View\View
     * @internal param $page
     * @internal param Request $request
     */
    public function apps()
    {
        $css = 'app';
        $games = Game::where('type', 'apps')->latest('update')->paginate(20);
        $categories = Category::where('type', 'apps')->latest()->take(7)->get();
        $pageApp = true;

        return view('games.app', compact('games', 'pageApp', 'categories', 'css'))->with([
            'title' => 'Hot Android Apps Free download - AppForAndroidPhone',
            'desc' => 'Looking for some hot apps to play on your Android device? AppForAndroidPhone.com supports top best new Android apps download.',
            'keyword' => 'hot android apps, free android apps'
        ]);
    }


    public function details($slug)
    {
        $css = 'details';
        $page = 'Details';
        $game = Game::where('slug', $slug)->first();
        $relates = Game::where('category_id', $game->category_id)->take(8)->get();
        return view('games.details', compact('game', 'page', 'relates', 'css'))->with([
            'title' => $game->title . ' for Android Free Download - AppForAndroidPhone',
            'desc' => $game->title . ' is a kind of ' . $game->category->name . ' ' . $game->type . ' for Android, AppForAndroidPhone official website provides download and walkthrough for ' . $game->title . ', Play free ' . $game->title . ' online.',
            'keyword' => $game->title
        ]);
    }

    public function gameCategories()
    {
        $css = 'app';
        $page = 'Categories';
        $categories = Category::where('type', 'games')->get();
        return view('games.categories', compact('categories', 'page', 'css'))->with([
            'title' => 'Android Games Categories - AppForAndroidPhone',
            'desc' => 'AppForAndroidPhone provide thousands hot and popular Android games that will satisfy the needs of all types for you.',
            'keyword' => 'android games categories'
        ]);
    }

    public function appCategories()
    {
        $css = 'app';
        $page = 'Categories';
        $categories = Category::where('type', 'apps')->get();
        return view('games.categories', compact('categories', 'page', 'css'))->with([
            'title' => 'Android Apps Categories - AppForAndroidPhone',
            'desc' => 'AppForAndroidPhone provide thousands hot and popular Android apps that will satisfy the needs of all types for you.',
            'keyword' => 'android apps categories'
        ]);
    }

    /**
     * http request from javascript
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function suggestion(Request $request)
    {
        $games = Game::tagged($request->input('term'))->latest('update')->take(10)->get();
        return response()->json($games);
    }

    /**
     * download game.
     * @param $gameId
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function download($gameId)
    {
        $crawler = new MainCrawler();
        return $crawler->download($gameId);
    }

}
