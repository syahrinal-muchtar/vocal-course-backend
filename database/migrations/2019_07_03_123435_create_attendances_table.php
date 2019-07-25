<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAttendancesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('attendances', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->date('date')->nullable();
			$table->integer('teacher_id')->nullable()->index('attendanceXteacher');
			$table->integer('teacher_status')->nullable()->default(0);
			$table->integer('schedule_id')->nullable()->index('attendanceXschedules');
			$table->integer('student_id')->nullable()->index('attendanceXstudent');
			$table->integer('student_status')->nullable()->default(0);
			$table->integer('class_id')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('attendances');
	}

}
