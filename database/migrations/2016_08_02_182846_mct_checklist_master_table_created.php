<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MctChecklistMasterTableCreated extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('mct_checklist_master', function(Blueprint $table)
		{
			$table->bigIncrements('checklist_id');
			$table->integer('i_action_id');
			$table->integer('i_reference_week');	
			$table->string('v_reference_descrip',255);
			$table->string('v_action_descrip',5000);
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
		Schema::drop('mct_checklist_master');
	}

}
