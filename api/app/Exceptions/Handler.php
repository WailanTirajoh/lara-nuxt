<?php

namespace App\Exceptions;

use App\Http\Helpers\ApiResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
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
        $this->renderable(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => 'Record not found.',
                ], Response::HTTP_NOT_FOUND);
            }
        });
    }

    public function render($request, Throwable $e)
    {
        if ($e) {
            $statusCode = match (true) {
                $e instanceof HttpException => $e->getStatusCode(),
                $e instanceof AuthenticationException => 401,
                default => Response::HTTP_INTERNAL_SERVER_ERROR,
            };

            return match (true) {
                $e instanceof HttpResponseException => $e->getResponse(),
                $e instanceof AuthenticationException => $this->unauthenticated($request, $e),
                $e instanceof ValidationException => $this->convertValidationExceptionToResponse($e, $request),
                default => ApiResponse::error(
                    message: $e->getMessage(),
                    statusCode: $statusCode
                ),
            };
        }

        return parent::render($request, $e);
    }
}
