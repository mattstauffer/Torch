<?php

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Contracts\Debug\ExceptionHandler as ExceptionHandlerContract;

class ExceptionHandler implements ExceptionHandlerContract
{
    public function shouldReport(Throwable $e): bool
    {
        return false;
    }

    public function report(Throwable $e): void
    {
        throw $e;
    }

    public function render($request, Throwable $e): Response
    {
        throw $e;
    }

    public function renderForConsole($output, Throwable $e): void
    {
        throw $e;
    }
};