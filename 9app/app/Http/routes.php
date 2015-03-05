<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

use App\Category;
use App\Game;

Route::get('test', function(){
    return view('hentai');
});

Route::get('crawler', 'CrawlerController@index');

//main
Route::get('/', 'MainController@index');
//ajax
Route::post('suggestion', 'MainController@suggestion');


//categories list
Route::get('android-games-categories', 'MainController@gameCategories');
Route::get('android-apps-categories', 'MainController@appCategories');
//list all.
Route::get('android-games', 'MainController@games');
Route::get('android-apps', 'MainController@apps');

//search
Route::get('search', function(){
    $css = 'search';
    $page = null;
    $term = null;
    $pageSearch = true;
    $games = Game::latest('update')->paginate(20);
    return view('games.search', compact('games', 'term', 'pageSearch', 'css', 'page'));
});
Route::get('search/{tag}', function($tag) {
    if (preg_match('/tag-([a-z0-9\-]+)/', $tag, $matches)) {
        $css = 'search';
        $page = null;
        $pageSearch = true;
        $term = $matches[1];
        if (strlen($term) > 2) {
            $term = str_replace('+',' ', $term);
            $keyword = Keyword::where('name', $term)->first();
            if (!$keyword) {
                $keyword = new Keyword();
                $keyword->create(['name' => $term, 'count' => 1]);
            } else {
                $keyword->count ++;
                $keyword->save();
            }
            $games = Game::tagged($term)->latest('update')->paginate(20);
        } else {
            $games = Game::latest('update')->paginate(20);
        }
        return view('games.search', compact('games', 'term', 'pageSearch', 'css', 'page'));
    }
});

//categories details

Route::get('android/{value}', function($value){
    if (preg_match('/top-([a-z0-9\-]+)-(apps|games)/', $value, $matches)) {
        $css = 'app';
        $category = Category::where('slug', $matches[1])->first();
        $games = Game::where('category_id', $category->id)->paginate(20);
        $page = $category->name;
        return view('games.category', compact('category', 'games', 'page', 'css'));
    }
});
//app details
Route::get('android-apps/{slug}', 'MainController@details');

//game details
Route::get('android-games/{slug}', 'MainController@details');









Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);