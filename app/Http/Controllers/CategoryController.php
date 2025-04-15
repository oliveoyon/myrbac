<?php

namespace App\Http\Controllers;
use App\Models\Category;

use Illuminate\Http\Request;
use App\Services\LogService;

class CategoryController extends Controller
{
    public function categories()
    {
        $categories = Category::all();
        return view('dashboard.admin.category', compact('categories'));
    }
    // Function to Add a New Category
    public function categoryAdd(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        $category = new Category();
        $category->name = $request->name;
        $category->save();

        LogService::logAction('Category Create', [
            'category_id' => $category->id,
            'name' => $category->name,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Category added successfully!',
            'category' => $category,
        ]);
    }

    public function categoryUpdate(Request $request, $categoryId)
    {
        $request->validate([
            'name' => 'required|unique:categories,name,' . $categoryId,
        ]);

        $category = Category::findOrFail($categoryId);
        $oldName = $category->name;

        $category->name = $request->name;
        $category->save();

        LogService::logAction('Category Update', [
            'category_id' => $categoryId,
            'changed_fields' => "Name changed from '{$oldName}' to '{$category->name}'",
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Category updated successfully!',
            'category' => $category,
        ]);
    }

    public function categoryDelete($categoryId)
    {
        $category = Category::findOrFail($categoryId);
        $categoryName = $category->name;
        $category->delete();

        LogService::logAction('Category Delete', [
            'category_id' => $categoryId,
            'deleted_name' => $categoryName,
            'message' => "Category '{$categoryName}' (ID: {$categoryId}) was deleted.",
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully!',
        ]);
    }

}
