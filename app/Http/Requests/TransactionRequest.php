<?php

namespace App\Http\Requests;

class TransactionRequest extends ApiFormRequest
{
  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    return [
      'category_id' => ['nullable', 'exists:categories,id'],
      'amount' => ['required', 'numeric'],
      'description' => ['nullable', 'string', 'max:255'],
      'happened_at' => ['nullable', 'date'],
      'type' => ['required', 'in:income,expense'],
    ];
  }

  public function attributes(): array
  {
    return [
      'category_id' => 'categoria',
      'amount' => 'valor',
      'description' => 'descrição',
      'happened_at' => 'data do lançamento',
      'type' => 'tipo',
    ];
  }
}
