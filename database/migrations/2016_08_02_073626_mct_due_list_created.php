<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MctDueListCreated extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('mct_due_list', function(Blueprint $table)
		{
			$table->bigIncrements('due_id');
			$table->bigInteger('fk_b_id');
			$table->bigInteger('fk_cc_id');
			$table->integer('fk_action_id');
			$table->date('dt_intervention_date');
			$table->tinyInteger('reminder_status');	
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('mct_due_list');
	}

}
