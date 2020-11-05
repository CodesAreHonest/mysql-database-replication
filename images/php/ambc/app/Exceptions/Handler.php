<?php

namespace App\Exceptions;

use Illuminate\Database\QueryException;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Throwable;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param Throwable $exception
     *
     * @return void
     * @throws Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param           $request
     * @param Throwable $exception
     *
     * @return Response|JsonResponse
     * @throws InternalServerError
     * @throws NotFound
     * @throws Throwable
     */
    public function render($request, Throwable $exception)
    {

        DB::rollBack();

        switch ($exception) {
            case $exception instanceof MethodNotAllowedHttpException:
                throw new NotFound(
                    'NOT_FOUND',
                    'The provide url is not found',
                    'The provide url is not found.'
                );
            case $exception instanceof QueryException:
                throw new InternalServerError(
                    'QUERY_EXCEPTION',
                    config('error.server.QUERY_EXCEPTION'),
                    $exception->getMessage()
                );
            case $exception instanceof BadRequest:
                return $exception->render(); // 400
            case $exception instanceof Unauthenticated:
                return $exception->render(); // 401
            case $exception instanceof Forbidden:
                return $exception->render(); // 403
            case $exception instanceof NotFound:
                return $exception->render(); // 404
            case $exception instanceof UnprocessableEntity:
                return $exception->render(); // 422
            case $exception instanceof InternalServerError:
                $this->reportSentry($exception);
                return $exception->render(); // 500
            case $exception instanceof BadGateway:
                $this->reportSentry($exception);
                return $exception->render(); // 502
            default:
                $this->reportSentry($exception);
                return parent::render($request, $exception);
        }
    }

    private function reportSentry($exception)
    {
        if ( env('APP_ENV') === 'production' ) {
            if ( app()->bound('sentry') && $this->shouldReport($exception) ) {
                app('sentry')->captureException($exception);
            }
        }
    }
}
