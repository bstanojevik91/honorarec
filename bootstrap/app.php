<?php

use App\Http\Middleware\EnsureAdmin;
use App\Http\Middleware\EnsureEmployer;
use App\Http\Middleware\EnsureEmployerVerified;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => EnsureAdmin::class,
            'employer' => EnsureEmployer::class,
            'employer.verified' => EnsureEmployerVerified::class,
        ]);

        RedirectIfAuthenticated::redirectUsing(function ($request): string {
            $user = $request->user();

            if ($request->is('admin') || $request->is('admin/*')) {
                return $user?->is_admin
                    ? route('admin.dashboard')
                    : route('home');
            }

            if ($request->is('employer') || $request->is('employer/*')) {
                return $user && ! $user->is_admin && $user->company_id
                    ? route('employer.dashboard')
                    : route('home');
            }

            return $user?->is_admin ? route('admin.dashboard') : route('home');
        });

        Authenticate::redirectUsing(function ($request): string {
            if ($request->is('admin') || $request->is('admin/*')) {
                return route('admin.login');
            }

            if ($request->is('employer') || $request->is('employer/*')) {
                return route('employer.login');
            }

            return route('login');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

$defaultPublicPath = dirname(__DIR__) . '/public';
$cpanelPublicPath = dirname(__DIR__, 2) . '/public_html';

$app->usePublicPath(is_dir($cpanelPublicPath) ? $cpanelPublicPath : $defaultPublicPath);

return $app;
