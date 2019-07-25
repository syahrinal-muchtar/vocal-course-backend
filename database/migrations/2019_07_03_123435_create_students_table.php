<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStudentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('students', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('first_name', 20)->nullable();
			$table->string('middle_name', 20)->nullable();
			$table->string('last_name', 20)->nullable();
			$table->dateTime('birth_date')->nullable();
			$table->integer('age')->nullable();
			$table->enum('sex', array('M','F'))->nullable();
			$table->string('street_address', 100)->nullable();
			$table->string('cell_phone', 12)->nullable();
			$table->string('home_phone_no', 12)->nullable();
			$table->string('school', 20)->nullable();
			$table->string('email', 50)->nullable();
			$table->string('person_responsible_for_bill', 50)->nullable();
			$table->string('reason_choose_us', 100)->nullable();
			$table->string('instructor_audition', 50)->nullable();
			$table->string('audition_results', 12)->nullable();
			$table->integer('class_id')->nullable()->index('studentXclass');
			$table->integer('teacher_id')->nullable()->index('studentXteacher');
			$table->integer('result_days')->nullable();
			$table->integer('result_hours')->nullable();
			$table->string('signature_img_url')->nullable();
			$table->dateTime('date')->nullable();
			$table->integer('status')->nullable()->comment('0 = cancel
1 = trial
2 = unpaid
3 = paid');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('students');
	}

}
