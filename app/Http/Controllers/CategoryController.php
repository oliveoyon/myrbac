<?php

namespace App\Http\Controllers;
use App\Models\Category;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function categories()
    {
        $categories = Category::all();
        return view('dashboard.admin.category', compact('categories'));
    }
    // Function to Add a New Category
    public function districtAdd(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        // Create a new category
        $category = new Category();
        $category->name = $request->name;
        $category->save();

        return response()->json([
            'success' => true,
            'message' => 'Category added successfully!',
            'category' => $category,
        ]);
    }

    // Function to Update an Existing Category
    public function districtUpdate(Request $request, $districtId)
    {
        $request->validate([
            'name' => 'required|unique:categories,name,' . $districtId,
        ]);

        $category = Category::findOrFail($districtId);
        $category->name = $request->name;
        $category->save();

        

        return response()->json([
            'success' => true,
            'message' => 'Category updated successfully!',
            'category' => $category,
        ]);
    }

    // Function to Delete a Category
    public function districtDelete($districtId)
    {
        $category = Category::findOrFail($districtId);
        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully!',
        ]);
    }
}
