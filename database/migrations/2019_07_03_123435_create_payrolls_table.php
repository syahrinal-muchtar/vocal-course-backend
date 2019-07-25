<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePayrollsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('payrolls', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->dateTime('date')->nullable();
			$table->integer('teacher_id')->nullable()->index('payrollXteacher');
			$table->integer('students_count')->nullable();
			$table->integer('total_salary')->nullable();
			$table->integer('total_vacation')->nullable();
			$table->integer('total_absent')->nullable();
			$table->integer('total')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('payrolls');
	}

}
