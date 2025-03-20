<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
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
     * Convert an authentication exception into a response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return $this->shouldReturnJson($request, $exception)
                    ? response()->json(['status' =>'error','message' => $exception->getMessage()], 201)
                    : redirect()->guest($exception->redirectTo() ?? route('login'));
    }


    /**
     * Convert a validation exception into a JSON response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Validation\ValidationException  $exception
     * @return \Illuminate\Http\JsonResponse
     */
    protected function invalidJson($request, ValidationException $exception)
    {
        return response()->json([
            'status' => 'error',
            'message' => $exception->getMessage(),
            'errors' => $exception->errors(),
        ], 201);//$exception->status
    }

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {

            $userLevelCheck = $e instanceof \jeremykenedy\LaravelRoles\App\Exceptions\RoleDeniedException ||
            $e instanceof \jeremykenedy\LaravelRoles\App\Exceptions\RoleDeniedException ||
            $e instanceof \jeremykenedy\LaravelRoles\App\Exceptions\PermissionDeniedException ||
            $e instanceof \jeremykenedy\LaravelRoles\App\Exceptions\LevelDeniedException;
            if ($userLevelCheck) {
                //return response()->view('errors.custom', [], 500);
                abort(403, 'Access Denied!');
            }
            //
        });
    }
}
