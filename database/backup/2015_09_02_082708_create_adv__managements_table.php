<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdvManagementsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('adv__managements', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('plan_id')->unsigned();
            $table->integer('total_click');
            $table->integer('total_impression');
            $table->string('range');
            $table->text('categories');
            $table->text('subcategories');
            $table->text('images');
            $table->enum('role', ['Sidebar', 'Categories']);
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
		Schema::drop('adv__managements');
	}

}
