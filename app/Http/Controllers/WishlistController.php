<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\{Book, Wishlist};
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $books = Book::join('wishlists', 'books.id', '=', 'wishlists.book_id')
            ->where('user_id', auth()->user()->id)
            ->get('books.*');

        return view('book/wishlist', compact('books'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $book      = Book::find($request->bookId);
        $user      = Auth::user();
        $data      = array(
            'user_id' => $user->id,
            'book_id' => $book->id,
        );

        $wishlists = Wishlist::create($data);
        return response()->json(compact('wishlists'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user      = Auth::user();
        $book      = Book::find($id);
        $wishlists = Wishlist::where('user_id', $user->id)->where('book_id', $book->id)->delete();

        return response()->json()->status();
    }

    public function search(Request $request)
    {
        $wishlists = Auth::user()->wishlists;
        $books     = $wishlists->map(function ($wishlist) {
            global $request;

            $conditions = array(
                array('id', $wishlist->book_id),
                array('name', 'LIKE', "%$request->keywords%"),
            );

            return Book::where($conditions)->first();
        });

        $books = $books->filter(function ($books) {
            return $books != null;
        });

        $data   = compact('books');
        $render = view('layouts.books', $data)->render();

        return response()->json(compact('render'));
    }
}
