<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\Pin;
use App\Models\Board;
use App\Models\Comment;
use App\Policies\PinPolicy;
use App\Policies\BoardPolicy;
use App\Policies\CommentPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Pin::class => PinPolicy::class,
        Board::class => BoardPolicy::class,
        Comment::class => CommentPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        //
    }
}
