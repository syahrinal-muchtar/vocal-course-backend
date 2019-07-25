<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToPayrollsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('payrolls', function(Blueprint $table)
		{
			$table->foreign('teacher_id', 'payrollXteacher')->references('id')->on('teachers')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('payrolls', function(Blueprint $table)
		{
			$table->dropForeign('payrollXteacher');
		});
	}

}
