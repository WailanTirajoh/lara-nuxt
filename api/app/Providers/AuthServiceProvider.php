<?php

namespace App\Providers;

use App\Models\Channel;
use App\Models\Post;
use App\Models\React;
use App\Models\Reply;
use App\Models\Thread;
use App\Models\User;
use App\Policies\ChannelPolicy;
use App\Policies\PermissionPolicy;
use App\Policies\PostPolicy;
use App\Policies\ReactPolicy;
use App\Policies\ReplyPolicy;
use App\Policies\RolePolicy;
use App\Policies\ThreadPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Channel::class => ChannelPolicy::class,
        Post::class => PostPolicy::class,
        React::class => ReactPolicy::class,
        Reply::class => ReplyPolicy::class,
        Thread::class => ThreadPolicy::class,
        Role::class => RolePolicy::class,
        Permission::class => PermissionPolicy::class,
        User::class => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Implicitly grant "Super" role all permissions
        // This works in the app by using gate-related functions like auth()->user->can() and @can()
        Gate::before(function ($user) {
            return $user->hasRole('Super') ? true : null;
        });
    }
}
