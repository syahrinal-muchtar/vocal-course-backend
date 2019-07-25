<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePricingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pricings', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('class_id')->nullable()->index('PricingXClass');
			$table->integer('price')->nullable();
			$table->integer('total_meetup')->nullable();
			$table->integer('duration')->nullable();
			$table->integer('type_by_difficulty')->nullable();
			$table->integer('type_by_teacher')->nullable();
			$table->integer('type_by_participant')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('pricings');
	}

}
