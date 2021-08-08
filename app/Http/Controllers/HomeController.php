<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Category;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $discount_books = Book::where('discount', '!=', null)->orderBy('discount')->get();
        $comic_books    = Book::where('category_id', 1)->orderByDesc('rating')->get()->take(12);

        $books = array(
            'komik' => Book::where('category_id', 1)->orderByDesc('rating')->get()->take(12),
            'pendidikan' => Book::where('category_id', 1)->orderByDesc('rating')->get()->take(12),
            'komik' => Book::where('category_id', 1)->orderByDesc('rating')->get()->take(12),
        );

        return view('home');
    }
}
