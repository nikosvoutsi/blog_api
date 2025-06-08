<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Comment;


class CategoriesController extends Controller
{
    
public function getAllCategories(Request $request)
{
    try {
        $categories = Category::with('parent')->get();

        return response()->json([
            'success' => true,
            'categories' => $categories,
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'An error occurred while fetching categories.',
            'error' => $e->getMessage(),
        ], 500);
    }
}



}
