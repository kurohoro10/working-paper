<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        /**
         * 1. Handle Method Not Allowed (405)
         */
        $exceptions->render(function (MethodNotAllowedHttpException $e, Request $request): RedirectResponse|JsonResponse {
            if ($request->isXmlHttpRequest() || $request->expectsJson()) {
                return response()->json(['message' => 'Method not supported.'], 405);
            }

            // Redirecting to dashboard as requested
            return redirect()->route('dashboard')
                ->with('error', 'Invalid action. You cannot access that URL directly.');
        });

        /**
         * 2. Handle Access Denied (403)
         */
        $exceptions->render(function (AccessDeniedHttpException $e, Request $request):RedirectResponse|JsonResponse {
            if ($request->isXmlHttpRequest() || $request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }

            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access that page.');
        });
    })->create();
