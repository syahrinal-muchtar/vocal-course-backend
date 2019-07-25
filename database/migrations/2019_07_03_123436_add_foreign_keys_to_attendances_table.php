<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToAttendancesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('attendances', function(Blueprint $table)
		{
			$table->foreign('schedule_id', 'attendanceXschedules')->references('id')->on('schedules')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('student_id', 'attendanceXstudent')->references('id')->on('students')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('teacher_id', 'attendanceXteacher')->references('id')->on('teachers')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('attendances', function(Blueprint $table)
		{
			$table->dropForeign('attendanceXschedules');
			$table->dropForeign('attendanceXstudent');
			$table->dropForeign('attendanceXteacher');
		});
	}

}
