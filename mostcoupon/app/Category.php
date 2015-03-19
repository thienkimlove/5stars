<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model {

	protected $fillable = ['name', 'icon', 'link', 'page'];

    /**
     * When title change then slug will change.
     * @param $name
     * @internal param $title
     */
    public function setNameAttribute($name)
    {
        $this->attributes['name'] = $name;
        $this->attributes['slug'] =  Str::limit( Str::slug($name), 32, '');
    }

    /**
     * one category may have many stores.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function stores()
    {
       return $this->HasMany('App\Store');
    }

}
