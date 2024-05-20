<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(): JsonResponse
    {
        $categories = Category::with('posts', 'posts.user')->orderBy('created_at', 'desc')->get();
        // return response()->json($categories);
        return response()->json([
            'categories' => CategoryResource::collection($categories)
        ]);
    }

    public function show(string $slug): JsonResponse
    {
        $category = Category::where('slug', $slug)->first();
        return response()->json([
            'category' => CategoryResource::make($category)
        ]);
    }

    public function showWithPosts(string $slug): JsonResponse
    {
        $category = Category::where('slug', $slug)->with('posts')->orderBy('posts.created_at', 'desc')->first();
        return response()->json( [
                'category' => CategoryResource::make($category)
            ]);
    }
}
