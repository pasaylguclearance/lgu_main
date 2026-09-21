<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Auth;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        // Expired session / stale CSRF token (419 "Page Expired").
        if ($exception instanceof TokenMismatchException) {
            // Signing out with a stale token: the user wants to leave anyway —
            // finish the logout instead of showing a 419.
            if ($request->is('logout')) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login');
            }

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your session has expired. Please reload the page and try again.',
                ], 419);
            }

            // Any other form: go back with a message and the typed input (minus passwords).
            return redirect()->back()
                ->withInput($request->except($this->dontFlash))
                ->withErrors(['session' => 'Your session has expired. Please try again.']);
        }

        return parent::render($request, $exception);
    }
}
