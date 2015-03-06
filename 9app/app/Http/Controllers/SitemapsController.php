<?php namespace App\Http\Controllers;

use App\Game;
use App\Http\Requests;
use Watson\Sitemap\Facades\Sitemap;

class SitemapsController extends Controller {

	public function index()
	{
        // You can use the route helpers too.
        Sitemap::addSitemap(route('games'));

        // Return the sitemap to the client.
        return Sitemap::renderSitemapIndex();
	}

    public function games() {
        $games = Game::all();
        foreach ($games as $game) {
            Sitemap::addTag(route('android-games', $game->slug), $game->created_at, 'daily', '0.8');
        }
        return Sitemap::renderSitemap();
    }

}
