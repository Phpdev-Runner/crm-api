<?php
/**
 * Created by PhpStorm.
 * User: Work
 * Date: 11.09.2017
 * Time: 11:04
 */

namespace Tests\Database;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DatabaseTest extends TestCase
{
    public function testDB()
    {
        $this->assertDatabaseHas('users', [
            'email' => 'admin@gmail.com'
        ]);
    }

    public function testDatabaseHasNot()
    {
        $this->assertDatabaseMissing('users',[
           'email' => 'missing@gmail.com'
        ]);
    }
}