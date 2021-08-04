<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class ContentSearchFilterController extends Controller
{
    public function bookFilter(Request $request)
    {
        $page    = ($request->page * 10) - 10;
        $books   = Book::where('name', 'LIKE', "%$request->keywords%")->get()->skip($page)->take(10); // Return Collection Object
        $between = array($request->min, $request->max);

        switch ($request->filter) {
            case 'relevan':
                $books = $books->whereBetween('price', $between);
                break;
            case 'lowest-rating':
                $books = $books->sortBy('rating')->whereBetween('price', $between);
                break;
            case 'highest-rating':
                $books = $books->sortByDesc('rating')->whereBetween('price', $between);
                break;
            case 'lowest-price':
                $books = $books
                    ->sortBy(function ($book) {
                        return $book->price - $book->discount;
                    })
                    ->whereBetween('price', $between);
                break;
            case 'highest-price':
                $books = $books
                    ->sortByDesc(function ($book) {
                        return $book->price - $book->discount;
                    })
                    ->whereBetween('price', $between);
                break;
        }

        $view = view('layouts.books', compact('books'))->render();

        return response()->json(compact('books', 'view', 'page'));
    }
}
