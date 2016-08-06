<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MctCallchampionReportCreated extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('mct_callchampion_report', function(Blueprint $table)
		{
			$table->bigIncrements('b_id');
			$table->bigInteger('fk_due_id');
			$table->enum('e_call_status', array('Not called', 'Received','Not Received'));
			$table->dateTime('dt_modify_date');
			$table->text('t_conversation');
			$table->text('t_action_items');
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
		Schema::drop('mct_callchampion_report');
	}

}
