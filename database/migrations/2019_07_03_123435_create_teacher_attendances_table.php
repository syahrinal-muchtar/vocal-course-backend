<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTeacherAttendancesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('teacher_attendances', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('teacher_id')->nullable()->index('attendancesXteacher');
			$table->date('date')->nullable();
			$table->integer('status')->nullable()->default(0);
			$table->integer('schedule_id')->nullable()->index('attendancesXschedule');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('teacher_attendances');
	}

}
