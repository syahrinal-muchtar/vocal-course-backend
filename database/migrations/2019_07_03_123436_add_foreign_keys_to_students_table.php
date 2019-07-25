<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToStudentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('students', function(Blueprint $table)
		{
			$table->foreign('class_id', 'studentXclass')->references('id')->on('classes')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('teacher_id', 'studentXteacher')->references('id')->on('teachers')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('students', function(Blueprint $table)
		{
			$table->dropForeign('studentXclass');
			$table->dropForeign('studentXteacher');
		});
	}

}
