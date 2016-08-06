<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MctCallChampionsCreated extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('mct_call_champions', function(Blueprint $table)
		{
			$table->bigIncrements('cc_id');
			$table->bigInteger('fk_user_id');
			$table->integer('i_max_beneficiary');
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
		Schema::drop('mct_call_champions');
	}

}
