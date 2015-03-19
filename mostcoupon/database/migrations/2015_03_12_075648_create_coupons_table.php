<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouponsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('coupons', function(Blueprint $table)
		{
			$table->increments('id');
            $table->string('title');
            $table->string('slug')->unique();
            $table->integer('store_id')->unsigned();

            $table->string('product_link');
            $table->integer('event_id')->unsigned();
            $table->text('desc');
            $table->string('coupon_type', 20);
            $table->string('coupon_code');

            $table->timestamp('expired_date');
            $table->timestamp('published_date');
			$table->timestamps();

            $table->foreign('store_id')
                 ->references('id')
                 ->on('stores')
                 ->onDelete('cascade');
            $table->foreign('event_id')
                  ->references('id')
                  ->on('events');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('coupons');
	}

}
