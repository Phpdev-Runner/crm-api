<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\User;

class LoginTest extends DuskTestCase
{
//    use DatabaseMigrations;
    use DatabaseTransactions;
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/api/v1/login')
                    ->assertSee('login');
        });
    }

    public function testAdminLogin()
    {
        $user = factory(User::class)->create([
            'name' => 'Ricki Martin',
            'email' => 'taylor@laravel.com',
        ]);

        $this->browse(function ($browser) use ($user) {
            $browser->visit('/api/v1/login')
                ->type('email', $user->email)
                ->type('password', 'secret')
                ->press('Login')
                ->assertSee('logged in as Ricki Martin!');
        });

        $this->artisan('migrate:refresh');
        $this->artisan('db:seed');

    }

//    public function testMultiBrowsers()
//    {
//        $this->browse(function($first,$second){
//            $first->loginAs(User::find(1))
//                ->visit('/')
//                ->waitForText('logged in as admin!');
//
//            $second->loginAs(User::find(2))
//                ->visit('/')
//                ->waitForText('logged in as manager!');
//        });
//    }
}
