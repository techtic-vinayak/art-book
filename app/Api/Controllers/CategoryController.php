<?php

namespace App\Api\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;

/**
 * @resource Reply
 */
class CategoryController extends Controller
{
    public function index()
    {
        $category = Category::get();
        return response()->json([
                'status_code'   => 200,
                'category'       => $category
        ]);
    }
}
