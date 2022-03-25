<?php

namespace App\Exceptions;

// use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Arr;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $exception)
    {
        if ($request->expectsJson()) {
            if ($exception instanceof ModelNotFoundException) {
                return response()->json(['error' => trans('responses.resource_not_found')], 404);
            }
        }

        return parent::render($request, $exception);
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => trans('responses.unauthenticated')], 401);
        }

        $guard = Arr::get($exception->guards(), 0);

        switch ($guard) {
            case 'customer':
                $login = 'customer.login';
                break;
            default:
                $login = 'login';
                break;
        }

        return redirect()->guest(route($login));
    }

    public function handle($request, Closure $next, $guard = null)
    {
        switch ($guard) {
            case 'customer':
                if (Auth::guard($guard)->check()) {
                    return redirect()->route('customer.dashboard');
                }
                break;
            case 'user':
                if (Auth::guard($guard)->check()) {
                    return redirect()->route('admin.dashboard');
                }
                break;
            default:
                if (Auth::guard($guard)->check()) {
                    return redirect('/');
                }
                break;
        }

        return $next($request);
    }
}
