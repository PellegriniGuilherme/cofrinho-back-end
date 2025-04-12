<?php

namespace App\Exceptions;

use App\Http\Responses\ApiResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
  public function render($request, Throwable $exception)
  {
    if ($request->is('api/*')) {
      if ($exception instanceof AuthenticationException) {
        return ApiResponse::error('Não autenticado', 401, ['alert' => true]);
      }

      if ($exception instanceof ValidationException) {
        return ApiResponse::error('Erro de validação', 422, [
          'errors' => $exception->errors(),
          'alert' => true,
        ]);
      }

      if ($exception instanceof ModelNotFoundException) {
        return ApiResponse::error('Recurso não encontrado', 404, ['alert' => true]);
      }

      if ($exception instanceof NotFoundHttpException) {
        return ApiResponse::error('Rota não encontrada', 404, ['alert' => true]);
      }

      return ApiResponse::error('Ocorreu um erro inesperado', 500, [
        'alert' => true,
        'exception' => config('app.debug') ? $exception->getMessage() : null
      ]);
    }

    return parent::render($request, $exception);
  }
}
