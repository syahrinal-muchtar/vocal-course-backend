<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToSchedulesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('schedules', function(Blueprint $table)
		{
			$table->foreign('branch_id', 'schedulesXbranches')->references('id')->on('branches')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('class_id', 'schedulesXclasses')->references('id')->on('classes')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('student_id', 'schedulesXstudents')->references('id')->on('students')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('teacher_id', 'schedulesXteachers')->references('id')->on('teachers')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('schedules', function(Blueprint $table)
		{
			$table->dropForeign('schedulesXbranches');
			$table->dropForeign('schedulesXclasses');
			$table->dropForeign('schedulesXstudents');
			$table->dropForeign('schedulesXteachers');
		});
	}

}
