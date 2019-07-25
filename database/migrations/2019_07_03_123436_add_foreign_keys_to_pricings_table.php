<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToPricingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('pricings', function(Blueprint $table)
		{
			$table->foreign('class_id', 'PricingXClass')->references('id')->on('classes')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('pricings', function(Blueprint $table)
		{
			$table->dropForeign('PricingXClass');
		});
	}

}
