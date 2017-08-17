<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeadCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lead_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });
        
        $leadCategories = config('lead_categories');
	    $leadCategoriesArray = [];
	    foreach ($leadCategories AS $key=>$value){
		    $leadCategoriesArray[] = [
			    'name'=>$value,
			    'created_at'=>\Carbon\Carbon::now(),
			    'updated_at'=>\Carbon\Carbon::now()
		    ];
	    }
	    DB::table('lead_categories')->insert($leadCategoriesArray);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lead_categories');
    }
}
