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

        return view(
            'home',
            array(
                'book_types' =>
                array(
                    'Buku Diskon'               => $discount_books,
                    'Rekomendasi Komik / Manga' => $comic_books,
                ),
            )
        );
    }
}
