<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Event extends Model {

	protected $fillable = ['name', 'desc', 'expired'];

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
     * event have many coupons
     */
    public function coupons()
    {
       return $this->hasMany('App\Coupon');
    }

}
