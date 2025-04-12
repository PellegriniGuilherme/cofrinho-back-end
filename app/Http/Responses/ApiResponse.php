<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;

class ApiResponse
{
  public static function success(mixed $data = [], string $message = 'Success', int $status = 200): JsonResponse
  {
    return response()->json([
      'status' => 'success',
      'message' => $message,
      'data' => $data,
    ], $status);
  }

  public static function error(string $message = 'An error occurred', int $status = 400, mixed $errors = null): JsonResponse
  {
    $response = [
      'status' => 'error',
      'message' => $message,
    ];

    if (!is_null($errors)) {
      $response['errors'] = $errors;
    }

    return response()->json($response, $status);
  }
}
