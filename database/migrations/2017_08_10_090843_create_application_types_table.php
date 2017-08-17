<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicationTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('application_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });
        
        $applicationTypes = config('constants.application_types');
        
        $applicationTypesArray = [];
        foreach ($applicationTypes AS $key=>$value){
			$applicationTypesArray[] = [
				'name'=>$value,
				'created_at'=>\Carbon\Carbon::now(),
				'updated_at'=>\Carbon\Carbon::now()
			];
        }
	    DB::table('application_types')->insert($applicationTypesArray);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('application_types');
    }
}
