<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToTransactionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('transactions', function(Blueprint $table)
		{
			$table->foreign('account_id', 'transactionXaccount')->references('id')->on('accounts')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('pricing_id', 'transactionXpricing')->references('id')->on('pricings')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('student_id', 'transactionXstudent')->references('id')->on('students')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('teacher_id', 'transactionXteacher')->references('id')->on('teachers')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('transaction_type_id', 'transactionXtransaction_type')->references('id')->on('transactions_type')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('transactions', function(Blueprint $table)
		{
			$table->dropForeign('transactionXaccount');
			$table->dropForeign('transactionXpricing');
			$table->dropForeign('transactionXstudent');
			$table->dropForeign('transactionXteacher');
			$table->dropForeign('transactionXtransaction_type');
		});
	}

}
