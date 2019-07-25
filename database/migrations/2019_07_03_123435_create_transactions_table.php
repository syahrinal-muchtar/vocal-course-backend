<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTransactionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('transactions', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->dateTime('date')->nullable();
			$table->integer('teacher_id')->nullable()->index('transactionXteacher');
			$table->integer('student_id')->nullable()->index('transactionXstudent');
			$table->dateTime('payment_date')->nullable();
			$table->integer('receipt_number')->nullable();
			$table->integer('cost')->nullable();
			$table->integer('pricing_id')->nullable()->index('transactionXpricing');
			$table->integer('royalty')->nullable();
			$table->text('note', 65535)->nullable();
			$table->integer('status')->nullable();
			$table->integer('account_id')->nullable()->index('transactionXaccount');
			$table->integer('transaction_type_id')->nullable()->index('transactionXtransaction_type');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('transactions');
	}

}
