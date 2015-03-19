<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Store extends Model {

     protected $fillable = ['name', 'desc', 'logo', 'active', 'website', 'category_id'];
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
     * Store have many coupons.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function coupons()
    {
       return $this->hasMany('App\Coupon');
    }

    /**
     * one stores may in some categories.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function category()
    {
        return $this->belongsTo('App\Category');
    }

}
