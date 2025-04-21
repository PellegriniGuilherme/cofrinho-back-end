<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Responses\ApiResponse;
use App\Models\Category;
use App\Services\Category\CategoryService;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
  public function __construct(private CategoryService $categoryService) {}

  public function index()
  {
    try {
      $user = Auth::user();
      $categories = $this->categoryService->index($user);

      return ApiResponse::success($categories, 'Categorias recuperadas com sucesso.');
    } catch (\Exception $e) {
      return ApiResponse::error('Erro ao buscar categorias', 422, ['alert' => true]);
    }
  }

  public function store(CategoryRequest $request)
  {
    try {
      $user = Auth::user();
      $this->categoryService->store($user, $request);

      return ApiResponse::success(['alert' => true], 'Categoria criada com sucesso');
    } catch (\Exception $e) {
      return ApiResponse::error('Erro salvar categoria', 422, ['alert' => true]);
    }
  }

  public function update(CategoryRequest $request, Category $category)
  {
    try {
      $user = Auth::user();
      $this->categoryService->update($user, $category, $request);

      return ApiResponse::success(['alert' => true], 'Categoria atualizada com sucesso');
    } catch (\Exception $e) {
      return ApiResponse::error('Erro atualizar categoria', 422, ['alert' => true]);
    }
  }

  public function destroy(Category $category)
  {
    try {
      $user = Auth::user();
      $this->categoryService->destroy($user, $category);

      return ApiResponse::success(['alert' => true], 'Categoria deletada com sucesso');
    } catch (\Exception $e) {
      return ApiResponse::error('Erro ao deletar categoria', 422, ['alert' => true]);
    }
  }
}
