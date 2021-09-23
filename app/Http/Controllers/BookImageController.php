<?php

namespace App\Http\Controllers;

use App\Models\{BookImage, User, Book};
use Illuminate\Support\Facades\{Auth, File, Validator, Storage};
use Illuminate\Http\Request;

class BookImageController extends Controller
{
    public function edit(Request $request, BookImage $bookImage)
    {
        $rules = array(
            'image' => 'required|mimes:jpg,jpeg,png|max:2000',
        );

        $validator = Validator::make($request->all(), $rules);

        if (!$validator->fails()) {
            $time     = time();
            $image    = $time . '.' . $request->image->getClientOriginalExtension();
            $filename = 'storage/books/book_images/' . $bookImage->image;
            $data     = array('image' => $image);

            File::delete($filename);
            $request->image->storeAs('public/books/book_images', $image);

            $update = $bookImage->update($data);
            $src    = $image;

            return response()->json(compact('update', 'src'));
        } else {
            $errors = $validator->errors();

            return response()->json(compact('errors'));
        }
    }

    public function destroy(BookImage $bookImage)
    {
        $delete = $bookImage->delete();

        $filename = 'storage/books/book_images/' . $bookImage->image;

        File::delete($filename);

        return response()->json(compact('delete'));
    }

    public function store(Request $request, Book $book)
    {
        $rules = array(
            'image' => 'required|mimes:png,jpg,jpeg|max:2000',
        );

        $validator = Validator::make($request->all(), $rules);

        $request->validate($rules);

        if (!$validator->fails()) {
            $time       = time();
            $image      = $time . '.' . $request->image->getClientOriginalExtension();
            $create     = array('image' => $image);
            $book_image = $book->book_images()->create($create);

            $request->image->storeAs('public/books/book_images', $image);

            return response()->json(compact('image', 'book_image'));
        } else {
            $errors = $validator->errors();

            return response()->json(compact('errors'));
        }
    }
}
