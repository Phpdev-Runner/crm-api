<?php

namespace App\Providers;

use App\Comment;
use App\CommunicationRecord;
use App\Lead;
use App\Policies\CommentPolicy;
use App\Policies\CommunicationRecordPolicy;
use App\Policies\LeadPolicy;
use App\Policies\UserPolicy;
use App\User;
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
        User::class => UserPolicy::class,
        Lead::class => LeadPolicy::class,

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
