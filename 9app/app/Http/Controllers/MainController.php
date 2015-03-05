<?php namespace App\Http\Controllers;

use App\Category;
use App\Game;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Keyword;
use Illuminate\Http\Request;

class MainController extends Controller {

    /**
     * @return \Illuminate\View\View
     */
    public function index()
	{
       $css = 'home';
       $pageHome = true;
       $games = Game::latest('update')->take(20)->get();

	   return view('games.home', compact('games', 'pageHome', 'css'));
	}


    /**
     * @param $type
     * @return \Illuminate\View\View
     * @internal param $page
     * @internal param Request $request
     */
    public function games()
    {
        $css = 'game';
        $games = Game::where('type', 'games')->latest('update')->paginate(20);
        $categories = Category::where('type', 'games')->latest()->take(7)->get();
        $pageGame = true;

        return view('games.app', compact('games', 'pageGame', 'categories', 'css'));
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

        return view('games.app', compact('games', 'pageApp', 'categories', 'css'));
    }



    public function details($slug)
    {
        $css = 'details';
        $page = 'Details';
        $game = Game::where('slug', $slug)->first();
        $relates  = Game::where('category_id', $game->category_id)->take(8)->get();
        return view('games.details', compact('game', 'page', 'relates', 'css'));
    }

    public function gameCategories()
    {
        $css = 'game';
        $page = 'Categories';
        $categories = Category::where('type', 'games')->get();
        return view('games.categories', compact('categories', 'page', 'css'));
    }

    public function appCategories()
    {
        $css = 'app';
        $page = 'Categories';
        $categories = Category::where('type', 'apps')->get();
        return view('games.categories', compact('categories', 'page', 'css'));
    }

    /**
     * http request from javascript
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function suggestion(Request $request){
        $games = Game::tagged($request->input('term'))->latest('update')->take(10)->get();
        return response()->json($games);
    }

}
