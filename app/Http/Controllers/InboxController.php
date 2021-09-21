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
            ->distinct('ratings.id')
            ->where($conditions)
            ->paginate(15);

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
