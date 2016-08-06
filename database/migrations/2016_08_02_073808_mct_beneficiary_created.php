<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MctBeneficiaryCreated extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('mct_beneficiary', function(Blueprint $table)
		{
			$table->bigIncrements('b_id');
			$table->bigInteger('fk_f_id');
			$table->string('v_name',255);
			$table->string('v_husband_name',255);
			$table->integer('i_age');
			$table->integer('v_phone_number');
			$table->integer('v_awc_number');
			$table->string('v_village_name');
			$table->date('dt_due_date');
			$table->text('t_notes');
			$table->string('v_language');
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
		Schema::drop('mct_beneficiary');
	}

}
