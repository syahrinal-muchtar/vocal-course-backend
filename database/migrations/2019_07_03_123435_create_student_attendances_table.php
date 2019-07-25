<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStudentAttendancesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('student_attendances', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('student_id')->nullable()->index('attendancesXstudent');
			$table->date('date')->nullable();
			$table->integer('status')->nullable()->default(0);
			$table->integer('schedule_id')->nullable()->index('attendancesXschedules');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('student_attendances');
	}

}
