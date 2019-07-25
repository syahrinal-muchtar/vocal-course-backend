<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSchedulesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('schedules', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('day', 11)->nullable();
			$table->time('time')->nullable();
			$table->integer('student_id')->nullable()->index('schedulesXstudents');
			$table->integer('teacher_id')->nullable()->index('schedulesXteachers');
			$table->integer('class_id')->nullable()->index('schedulesXclasses');
			$table->integer('branch_id')->nullable()->index('schedulesXbranches');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('schedules');
	}

}
