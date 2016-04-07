<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlansTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('plans', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('customer_id');
			$table->integer('user_id')->unsigned();
			$table->string('plan_type');
            $table->float('plan_price');
            $table->float('balance');
            $table->boolean('status');
            $table->boolean('auto_renew');
            $table->float('click_cost');
            $table->float('impression_cost');
            $table->float('daily_budget');
			$table->string('title');
            $table->text('description');
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
		Schema::drop('plans');
	}

}
