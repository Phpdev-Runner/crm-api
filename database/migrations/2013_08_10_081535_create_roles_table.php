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
        
        // adding default roles: admin, manager, unauthorized
	    DB::table('roles')->insert([
	    	[   'name'=>'admin',
			    'created_at' =>  \Carbon\Carbon::now(),
			    'updated_at' => \Carbon\Carbon::now()
		    ],
	    	[   'name'=>'manager',
			    'created_at' =>  \Carbon\Carbon::now(),
			    'updated_at' => \Carbon\Carbon::now()
		    ],
	    	[   'name'=>'unauthorized',
			    'created_at' =>  \Carbon\Carbon::now(),
			    'updated_at' => \Carbon\Carbon::now()
			]
	    ]);
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
