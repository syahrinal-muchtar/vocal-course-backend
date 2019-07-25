<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToStudentAttendancesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('student_attendances', function(Blueprint $table)
		{
			$table->foreign('schedule_id', 'attendancesXschedules')->references('id')->on('schedules')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('student_attendances', function(Blueprint $table)
		{
			$table->dropForeign('attendancesXschedules');
		});
	}

}
