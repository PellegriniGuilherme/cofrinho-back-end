<?php

namespace App\Http\Requests;

class CategoryRequest extends ApiFormRequest
{
  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    return [
      'name' => ['required', 'string', 'max:255'],
      'color' => ['required', 'string', 'size:7'],
    ];
  }
}
