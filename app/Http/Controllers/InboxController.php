<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InboxController extends Controller
{
    public function review(Request $request) {
        $select_value = array(
            'books.*',
            'ratings.rating',
            'ratings.review',
            'ratings.invoice',
            'ratings.created_at as rating_date',
            'book_user.completed_date',
        );

        $conditions = array(
            array('ratings.user_id', auth()->user()->id),
        );

        if ($request->review_keywords) {
            array_push($conditions, array('books.name', 'LIKE', "%$request->review_keywords%"));
            array_push($conditions, array('ratings.invoice', 'LIKE', "%$request->review_keywords%", 'OR'));
        }

        $books = DB::table('books')
            ->join('ratings', 'books.id', 'ratings.book_id')
            ->join('book_user', 'ratings.invoice', 'book_user.invoice')
            ->select($select_value)
            ->distinct('ratings.invoice')
            ->where($conditions)
            ->get();

        // Mapping date values dari 'book_user.completed_date' menjadi tanggal yang berbahasa indonesia
        $books = $books->map(function($book) {
            $completed_date = Carbon::make($book->completed_date)->isoFormat('dddd, DD MMMM YYYY HH:mm');
            $rating_date    = Carbon::make($book->rating_date)->isoFormat('dddd, DD MMMM YYYY HH:mm');

            $book->completed_date = $completed_date;
            $book->rating_date    = $rating_date;

            return $book;
        });

        $rating_text = array(
            'BURUK',
            'TIDAK BAIK',
            'CUKUP',
            'SANGAT BAIK',
            'SUPER BAIK',
        );

        $data = compact('books', 'rating_text');

        return view('inbox.my-review', $data);
    }
}
