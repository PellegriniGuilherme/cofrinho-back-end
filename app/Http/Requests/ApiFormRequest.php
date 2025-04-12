<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Http\Responses\ApiResponse;

abstract class ApiFormRequest extends FormRequest
{
  protected function failedValidation(Validator $validator)
  {
    throw new HttpResponseException(
      ApiResponse::error('Erro de validação', 422, $validator->errors())
    );
  }
}
