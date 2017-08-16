<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('roles', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name');
			$table->timestamps();
		});
		
		// adding default roles: admin, manager, unauthorized from config file
		$roles = config('roles');
		$insertArray = [];
		foreach ($roles AS $role){
			$insertArray[] = [
				'name' => $role,
				'created_at' =>  \Carbon\Carbon::now(),
				'updated_at' => \Carbon\Carbon::now()
			];
		}
		DB::table('roles')->insert($insertArray);
	}
	
	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('roles');
	}
}
