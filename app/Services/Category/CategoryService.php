<?php

namespace App\Services\Category;

class CategoryService
{
  public function index($user)
  {
    return $user->categories()
      ->orderBy('created_at', 'desc')
      ->get();
  }

  public function store($user, $request)
  {
    $category = $user->categories()->create([
      'name' => $request->name,
      'color' => $request->color,
    ]);

    return $category;
  }

  public function update($user, $category, $request)
  {
    if ($category->user_id !== $user->id) {
      throw new \Exception('Category not found');
    }

    $category->update([
      'name' => $request->name,
      'color' => $request->color,
    ]);

    return $category;
  }

  public function destroy($user, $category)
  {
    if ($category->user_id !== $user->id) {
      throw new \Exception('Category not found');
    }

    $category->delete();

    return response()->json(['message' => 'Category deleted successfully']);
  }
}
