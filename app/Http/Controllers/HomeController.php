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
        $data = array(
            'books' => array(
                array(
                    'title' => 'Buku Komik',
                    'data' => Category::find(1),
                    'search_url' => route('books.index', array('category' => array(Category::find(1)->id))),
                ),
                array(
                    'title' => 'Buku Sejarah',
                    'data' => Category::find(6),
                    'search_url' => route('books.index', array('category' => array(Category::find(6)->id))),
                ),
                array(
                    'title' => 'Buku Pendidikan',
                    'data' => Category::find(1),
                    'search_url' => route('books.index', array('category' => array(Category::find(1)->id))),
                ),
            ),
            'modal_content' => view('home.modal-content')->render(),
        );

        return view('home.index', $data);
    }
}
