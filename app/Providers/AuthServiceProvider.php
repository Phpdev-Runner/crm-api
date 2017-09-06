<?php

namespace App\Providers;

use App\Comment;
use App\CommunicationRecord;
use App\Policies\CommentPolicy;
use App\Policies\CommunicationRecordPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
        Comment::class => CommentPolicy::class,
        CommunicationRecord::class => CommunicationRecordPolicy::class,

    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
