<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Throwable;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Spatie\Honeypot\Exceptions\SpamException;

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
     * Register the exception handling callbacks for the application.
     */
    public function register()
    {
        $this->renderable(function (\Spatie\Honeypot\Exceptions\SpamException $e, \Illuminate\Http\Request $request) {
            \Log::debug('Caught SpamException');

            if ($request->routeIs('password.email')) {
                return redirect()
                    ->to('/password/reset')
                    ->withInput()
                    ->withErrors([
                        'email' => 'Spam detected. Please try again.',
                    ]);
            }

            return response('Spam detected', 422);
        });
    }


    /**
     * Override the render method to handle validation exceptions gracefully.
     */
    public function render($request, Throwable $exception)
    {
        // Optionally log validation exceptions for debugging
        if ($exception instanceof ValidationException) {
            Log::debug('ValidationException: ' . json_encode($exception->errors()));
        }

        return parent::render($request, $exception);
    }
}
