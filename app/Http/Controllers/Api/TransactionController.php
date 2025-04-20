<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransactionRequest;
use App\Http\Responses\ApiResponse;
use App\Models\Transaction;
use App\Services\Transaction\TransactionService;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
  public function __construct(private TransactionService $transactionService) {}

  public function index()
  {
    try {
      $user = Auth::user();
      $categories = $this->transactionService->index($user);

      return ApiResponse::success($categories, 'Transactions retrieved successfully');
    } catch (\Exception $e) {
      return ApiResponse::error('Erro ao buscar transações', 422, ['alert' => true]);
    }
  }

  public function dashboard()
  {
    try {
      $user = Auth::user();
      $balance = $this->transactionService->summary($user);

      return ApiResponse::success($balance, 'Dashboard retrieved successfully');
    } catch (\Exception $e) {
      return ApiResponse::error('Erro ao buscar dados', 422, ['alert' => true]);
    }
  }

  public function store(TransactionRequest $request)
  {
    try {
      $user = Auth::user();
      $this->transactionService->store($user, $request);

      return ApiResponse::success(['alert' => true], 'Transaction created successfully');
    } catch (\Exception $e) {
      return ApiResponse::error($e, 422, ['alert' => true]);
    }
  }

  public function update(TransactionRequest $request, Transaction $transaction)
  {
    try {
      $user = Auth::user();
      $this->transactionService->update($user, $transaction, $request);

      return ApiResponse::success(['alert' => true], 'Transaction updated successfully');
    } catch (\Exception $e) {
      return ApiResponse::error($e, 422, ['alert' => true]);
    }
  }

  public function destroy(Transaction $transaction)
  {
    try {
      $user = Auth::user();
      $this->transactionService->destroy($user, $transaction);

      return ApiResponse::success(['alert' => true], 'Transaction deleted successfully');
    } catch (\Exception $e) {
      return ApiResponse::error('Erro ao deletar transação', 422, ['alert' => true]);
    }
  }
}
