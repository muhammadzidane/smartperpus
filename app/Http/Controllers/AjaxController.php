<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Author, Book};
use Facade\FlareClient\Http\Response;

class AjaxController extends Controller
{
    public function ajaxRequestStore(Request $request) {
        $authors = Author::where('name', 'like', '%' .$request->search_value . '%')->get(array('id', 'name'));
        $books   = Book::where('name', 'like', '%' .$request->search_value . '%')->get(array('id', 'name'));

        return response()->json(
            array(
                'books'   => $books,
                'authors' => $authors,
            )
        );
    }
}
