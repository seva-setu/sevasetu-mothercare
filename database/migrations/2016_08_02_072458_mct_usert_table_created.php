<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MctUsertTableCreated extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('mct_user', function(Blueprint $table)
		{
			$table->bigIncrements('user_id');
			$table->string('v_name');
			$table->string('v_email');
			$table->string('v_password');
			$table->tinyInteger('ti_is_verified');
			$table->enum('e_gender',array('Male','Female'));
			$table->enum('e_status',array('Active', 'Inactive', 'Deleted'));
			$table->string('i_phone_number');
			$table->tinyInteger('v_role');
			$table->text('v_confirm_string');
			$table->string('v_language');
			$table->dateTime('dt_create_date');
			$table->dateTime('dt_last_login');
			$table->string('v_ip');
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
		Schema::drop('mct_user');
	}

}
