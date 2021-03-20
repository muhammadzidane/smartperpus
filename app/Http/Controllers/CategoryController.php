<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Category, Book};

class CategoryController extends Controller
{
    public function index($category) {
        $category = Category::where('name', ucwords(str_replace('-', ' ', $category)))->first();
        return view('category.index', compact('category'));
    }
}
