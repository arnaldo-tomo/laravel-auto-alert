<?php

namespace ArnaldoTomo\AutoAlert;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Throwable;

class ExceptionListener
{
    public function handle(Request $request, Throwable $e)
    {
        if (!$request->hasSession()) {
            return;
        }

        if ($e instanceof ValidationException) {
            return;
        }

        if ($e instanceof HttpException) {
            $status = $e->getStatusCode();
            if ($status >= 400) {
                $message = $e->getMessage() ?: (SymfonyResponse::$statusTexts[$status] ?? 'An error occurred.');
                $request->session()->now('error', "HTTP {$status}: {$message}");
            }
            return;
        }

        // General exceptions
        $message = config('app.debug') ? $e->getMessage() : 'An unexpected error occurred.';
        $request->session()->now('error', $message ?: 'An unexpected error occurred.');
    }
}
