<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (NotFoundHttpException $e, Request $request) {
            if (! $this->shouldReturnApiJson($request)) {
                return null;
            }

            return response()->json([
                'message' => 'Not Found',
                'status_code' => 404,
            ], 404);
        });

        $this->renderable(function (MethodNotAllowedHttpException $e, Request $request) {
            if (! $this->shouldReturnApiJson($request)) {
                return null;
            }

            return response()->json([
                'message' => 'Method Not Allowed',
                'status_code' => 405,
            ], 405);
        });

        $this->renderable(function (AuthenticationException $e, Request $request) {
            if (! $this->shouldReturnApiJson($request)) {
                return null;
            }

            return response()->json([
                'message' => 'Unauthorized',
                'status_code' => 401,
            ], 401);
        });

        $this->renderable(function (AuthorizationException $e, Request $request) {
            if (! $this->shouldReturnApiJson($request)) {
                return null;
            }

            return response()->json([
                'message' => 'Forbidden',
                'status_code' => 403,
            ], 403);
        });

        $this->renderable(function (ValidationException $e, Request $request) {
            if (! $this->shouldReturnApiJson($request)) {
                return null;
            }

            return response()->json([
                'message' => 'Validation failed',
                'status_code' => 422,
                'errors' => $e->errors(),
            ], 422);
        });

        $this->renderable(function (Throwable $e, Request $request) {
            if (! $this->shouldReturnApiJson($request)) {
                return null;
            }

            if ($e instanceof HttpExceptionInterface) {
                $status = $e->getStatusCode();
                $message = $status >= 500 ? 'Server Error' : ($e->getMessage() ?: 'HTTP Error');

                return response()->json([
                    'message' => $message,
                    'status_code' => $status,
                ], $status);
            }

            return response()->json([
                'message' => 'Server Error',
                'status_code' => 500,
            ], 500);
        });
    }

    protected function shouldReturnApiJson(Request $request): bool
    {
        return $request->is('api/*') || $request->expectsJson();
    }
}
