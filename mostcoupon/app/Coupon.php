<?php namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Coupon extends Model {

    protected $dates = ['expired_date', 'published_date'];

    protected $fillable = ['title', 'store_id', 'coupon_code', 'coupon_type', 'expired_date', 'published_date', 'desc', 'event_id', 'product_link'];
    /**
     * When title change then slug will change.
     * @param $title
     */
    public function setTitleAttribute($title)
    {
        $this->attributes['title'] = $title;
        $this->attributes['slug'] =  Str::limit( Str::slug($title), 32, '');
    }

    /**
     * format date input
     * @param $date
     */
    public function setPublishedDateAttribute($date)
    {
       $this->attributes['published_date'] = Carbon::parse($date);
    }

    /**
     * format date input
     * @param $date
     */
    public function setExpiredDateAttribute($date)
    {
        $this->attributes['expired_date'] = Carbon::parse($date);
    }

    /**
     * coupons belong to one store.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function store()
    {
       return $this->belongsTo('App\Store');
    }

    /**
     * coupons belong to one event.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function event()
    {
        return $this->belongsTo('App\Event');
    }
}
