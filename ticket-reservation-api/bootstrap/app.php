<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as BaseAuthenticate;
use Illuminate\Http\Request;
use App\Http\Middleware\LogRequestResponse;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Yetkisiz kullanıcılar için JSON yanıtı döndür
        $middleware->redirectGuestsTo(function (Request $request) {
            return response()->json(['error' => 'Unauthorized'], 401);
        });
        $middleware->append(LogRequestResponse::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // JWT Hataları
        $exceptions->renderable(function (TokenInvalidException $e, $request) {
            return response()->json(['error' => 'Token is invalid'], 401);
        });

        $exceptions->renderable(function (TokenExpiredException $e, $request) {
            return response()->json(['error' => 'Token has expired'], 401);
        });

        $exceptions->renderable(function (JWTException $e, $request) {
            return response()->json(['error' => 'Token error'], 401);
        });

        $exceptions->renderable(function (AuthenticationException $e, $request) {
            return response()->json(['error' => 'Unauthorized'], 401);
        });

        // Olmayan Endpoint (404) Hatası
        $exceptions->renderable(function (NotFoundHttpException $e, $request) {
            return response()->json(['error' => 'Endpoint not found'], 404);
        });

        // Genel Hata Yakalama (Diğer tüm hatalar için)
        $exceptions->renderable(function (Throwable $e, $request) {
            return response()->json([
                'error' => 'Something went wrong',
                'message' => $e->getMessage()
            ], 500);
        });
    })->create();

