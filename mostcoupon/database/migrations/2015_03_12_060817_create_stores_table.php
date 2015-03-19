<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoresTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('stores', function(Blueprint $table)
		{
			$table->increments('id');
            $table->string('name');
            $table->string('slug')->unique();
            $table->integer('category_id')->unsigned();
            $table->text('desc');
            $table->string('logo');
            $table->string('website');
            $table->string('active', true);
			$table->timestamps();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
		});
        /*Schema::create('category_store', function(Blueprint $table)
        {
           $table->integer('store_id')->unsigned()->index();
           $table->integer('category_id')->unsigned()->index();
           $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
           $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
           $table->timestamps();
        });*/
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        //Schema::drop('category_store');
		Schema::drop('stores');

	}

}
