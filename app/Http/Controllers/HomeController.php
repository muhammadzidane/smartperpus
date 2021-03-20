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
        $discount_books = Book::where('discount', '!=',null)->orderBy('discount')->get();
        $comic_books    = Category::where('name', 'Komik')->first()->books->sortByDesc('rating')->take(6);

        return view('home',
            array(
                'book_types' =>
                array(
                    'Buku Diskon'               => $discount_books,
                    'Rekomendasi Komik / Manga' => $comic_books,
                ),

                'book_categories' =>
                array(
                    'discount_books',
                    'komik',
                ),
            )
        );

        // $buku_jjk = 5.0;

        // for ($i=0; $i < 5 ; $i++) {
        //     for ($j=0; $j < 10; $j++) {
        //         $rating = $i . '.' . $j;

        //         if ((float) $rating == $buku_jjk) {
        //             for ($k=1; $k <= 9; $k++) {
        //                 if ($j == $k) {
        //                     for ($l=0; $l < $i ; $l++) {
        //                         echo  'half ';
        //                     }

        //                     echo '. star-half';
        //                 }

        //             }

        //             if ($j == 0) {
        //                 for ($l=0; $l < $i ; $l++) {
        //                     echo  'full ';
        //                 }

        //                 echo '. star-full';
        //             }
        //         }
        //         else {
        //             echo 'star ';
        //             break;
        //         }

        //     }
        // }

        // echo 'anjay';
    } // End method : index
}
